<?php
declare(strict_types=1);

final class ProductoAdmin
{
    /** Lista TODOS los productos (incluye inactivos) para el panel. */
    public static function listarTodos(): array
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.activo, p.destacado,
                       p.categoria_id, c.nombre AS categoria_nombre,
                       (SELECT COUNT(*) FROM producto_imagenes pi WHERE pi.producto_id = p.id) AS tiene_imagen
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                ORDER BY p.activo DESC, c.orden ASC, p.nombre ASC";

        return Database::getConnection()->query($sql)->fetchAll();
    }

    public static function obtener(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT p.*, 
                    (SELECT COUNT(*) FROM producto_imagenes pi WHERE pi.producto_id = p.id) AS tiene_imagen
             FROM productos p WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch();
        return $r !== false ? $r : null;
    }

    public static function crear(array $datos): int
    {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, destacado, activo)
                VALUES (:nombre, :desc, :precio, :cat, :dest, :act)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':desc'   => $datos['descripcion'] ?: null,
            ':precio' => $datos['precio'],
            ':cat'    => $datos['categoria_id'],
            ':dest'   => $datos['destacado'] ? 1 : 0,
            ':act'    => $datos['activo'] ? 1 : 0,
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function actualizar(int $id, array $datos): void
    {
        $sql = "UPDATE productos
                SET nombre = :nombre, descripcion = :desc, precio = :precio,
                    categoria_id = :cat, destacado = :dest, activo = :act
                WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            ':id'     => $id,
            ':nombre' => $datos['nombre'],
            ':desc'   => $datos['descripcion'] ?: null,
            ':precio' => $datos['precio'],
            ':cat'    => $datos['categoria_id'],
            ':dest'   => $datos['destacado'] ? 1 : 0,
            ':act'    => $datos['activo'] ? 1 : 0,
        ]);
    }

    public static function toggleActivo(int $id): bool
    {
        $stmt = Database::getConnection()->prepare(
            "UPDATE productos SET activo = NOT activo WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    /** Guarda o reemplaza la imagen del producto. */
    public static function guardarImagen(int $productoId, string $contenido, string $mime): void
    {
        $sql = "INSERT INTO producto_imagenes (producto_id, mime_type, contenido)
                VALUES (:id, :mime, :data)
                ON DUPLICATE KEY UPDATE
                    mime_type = VALUES(mime_type),
                    contenido = VALUES(contenido)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $productoId, PDO::PARAM_INT);
        $stmt->bindValue(':mime', $mime);
        $stmt->bindValue(':data', $contenido, PDO::PARAM_LOB);
        $stmt->execute();
    }

    /** Devuelve [contenido, mime] o null si no hay imagen. */
    public static function obtenerImagen(int $productoId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT contenido, mime_type, UNIX_TIMESTAMP(actualizado) AS ts
             FROM producto_imagenes WHERE producto_id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $productoId]);
        $r = $stmt->fetch();
        return $r !== false ? $r : null;
    }
}
