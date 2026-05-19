<?php
declare(strict_types=1);

final class Pedido
{
    /**
     * Crea un pedido en transacción atómica.
     * $items = [['id' => int, 'qty' => int], ...] (precio se busca en BD)
     * Devuelve el ID del pedido creado.
     */
    public static function crear(int $clienteId, array $items, ?string $observaciones): int
    {
        $db = Database::getConnection();

        if (empty($items)) {
            throw new RuntimeException('El carrito está vacío.');
        }

        // 1. Recoger IDs únicos y consultar productos válidos
        $ids = array_unique(array_map(fn($i) => (int)$i['id'], $items));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $db->prepare(
            "SELECT id, nombre, precio FROM productos
             WHERE id IN ($placeholders) AND activo = 1"
        );
        $stmt->execute(array_values($ids));
        $productos = [];
        foreach ($stmt->fetchAll() as $p) {
            $productos[(int)$p['id']] = $p;
        }

        if (empty($productos)) {
            throw new RuntimeException('Ningún producto del carrito está disponible.');
        }

        // 2. Construir líneas válidas con precio del servidor (no del cliente)
        $lineas = [];
        $total = 0.0;

        foreach ($items as $item) {
            $pid = (int)$item['id'];
            $qty = max(1, (int)$item['qty']);

            if (!isset($productos[$pid])) {
                // Producto desactivado o eliminado, lo saltamos en silencio
                continue;
            }

            $precio = (float)$productos[$pid]['precio'];
            $lineas[] = [
                'producto_id' => $pid,
                'cantidad'    => $qty,
                'precio'      => $precio,
            ];
            $total += $precio * $qty;
        }

        if (empty($lineas)) {
            throw new RuntimeException('No se pudo procesar ninguna línea del pedido.');
        }

        // 3. Transacción: insertar cabecera + detalle, o nada
        $db->beginTransaction();
        try {
            $stmtPedido = $db->prepare(
                "INSERT INTO pedidos (cliente_id, total, observaciones, estado)
                 VALUES (:c, :t, :o, 'pendiente')"
            );
            $stmtPedido->execute([
                ':c' => $clienteId,
                ':t' => $total,
                ':o' => $observaciones ?: null,
            ]);
            $pedidoId = (int)$db->lastInsertId();

            $stmtLinea = $db->prepare(
                "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario)
                 VALUES (:pid, :prod, :qty, :precio)"
            );

            foreach ($lineas as $l) {
                $stmtLinea->execute([
                    ':pid'    => $pedidoId,
                    ':prod'   => $l['producto_id'],
                    ':qty'    => $l['cantidad'],
                    ':precio' => $l['precio'],
                ]);
            }

            $db->commit();
            return $pedidoId;

        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /** Pedido con sus líneas, validando que pertenezca al cliente (o sea admin) */
    public static function obtener(int $pedidoId, ?int $clienteId = null): ?array
    {
        $sql = "SELECT p.id, p.cliente_id, p.fecha, p.estado, p.total, p.observaciones,
                       c.nombre AS cliente_nombre, c.username AS cliente_username, c.curso_grupo
                FROM pedidos p
                INNER JOIN clientes c ON c.id = p.cliente_id
                WHERE p.id = :id";

        $params = [':id' => $pedidoId];

        if ($clienteId !== null) {
            $sql .= " AND p.cliente_id = :cli";
            $params[':cli'] = $clienteId;
        }

        $sql .= " LIMIT 1";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);
        $pedido = $stmt->fetch();
        if ($pedido === false) return null;

        // Detalle
        $stmt = Database::getConnection()->prepare(
            "SELECT dp.cantidad, dp.precio_unitario, dp.subtotal,
                    pr.id AS producto_id, pr.nombre AS producto_nombre
             FROM detalle_pedido dp
             INNER JOIN productos pr ON pr.id = dp.producto_id
             WHERE dp.pedido_id = :id
             ORDER BY dp.id ASC"
        );
        $stmt->execute([':id' => $pedidoId]);
        $pedido['lineas'] = $stmt->fetchAll();

        return $pedido;
    }

    /** Historial de un cliente */
    public static function listarPorCliente(int $clienteId): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT p.id, p.fecha, p.estado, p.total,
                    (SELECT COUNT(*) FROM detalle_pedido dp WHERE dp.pedido_id = p.id) AS num_lineas
             FROM pedidos p
             WHERE p.cliente_id = :c
             ORDER BY p.fecha DESC"
        );
        $stmt->execute([':c' => $clienteId]);
        return $stmt->fetchAll();
    }

    /** Todos los pedidos (admin), filtrados opcionalmente por estado */
    public static function listarTodos(?string $estado = null): array
    {
        $sql = "SELECT p.id, p.fecha, p.estado, p.total, p.cliente_id,
                       c.nombre AS cliente_nombre, c.username AS cliente_username,
                       (SELECT COUNT(*) FROM detalle_pedido dp WHERE dp.pedido_id = p.id) AS num_lineas
                FROM pedidos p
                INNER JOIN clientes c ON c.id = p.cliente_id";

        $params = [];
        if ($estado !== null && $estado !== '') {
            $sql .= " WHERE p.estado = :e";
            $params[':e'] = $estado;
        }
        $sql .= " ORDER BY p.fecha DESC LIMIT 200";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Cambia el estado del pedido (solo admin) */
    public static function cambiarEstado(int $pedidoId, string $nuevoEstado): bool
    {
        $validos = ['pendiente','preparando','listo','entregado','cancelado'];
        if (!in_array($nuevoEstado, $validos, true)) {
            throw new InvalidArgumentException('Estado no válido.');
        }

        $stmt = Database::getConnection()->prepare(
            "UPDATE pedidos SET estado = :e WHERE id = :id"
        );
        return $stmt->execute([':e' => $nuevoEstado, ':id' => $pedidoId]);
    }
}
