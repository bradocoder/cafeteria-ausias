<?php
declare(strict_types=1);

final class Producto
{
    /**
     * Lista productos activos. Si se pasa categoría, filtra.
     */
    public static function listar(?int $categoriaId = null): array
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio,
                       p.imagen, p.imagen_thumb, p.destacado,
                       c.nombre AS categoria_nombre, c.id AS categoria_id
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1 AND c.activo = 1";

        $params = [];

        if ($categoriaId !== null) {
            $sql .= " AND p.categoria_id = :cat";
            $params[':cat'] = $categoriaId;
        }

        $sql .= " ORDER BY p.destacado DESC, c.orden ASC, p.nombre ASC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Productos destacados (para la home). */
    public static function destacados(int $limite = 4): array
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio,
                       p.imagen, p.imagen_thumb,
                       c.nombre AS categoria_nombre
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1 AND p.destacado = 1
                ORDER BY p.nombre ASC
                LIMIT :lim";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

	/** Obtiene un producto por su ID, o null si no existe. */
	public static function obtenerPorId(int $id): ?array
	{
    $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio,
                   p.imagen, p.imagen_thumb, p.destacado,
                   c.nombre AS categoria_nombre, c.id AS categoria_id
            FROM productos p
            INNER JOIN categorias c ON c.id = p.categoria_id
            WHERE p.id = :id AND p.activo = 1
            LIMIT 1";

    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row !== false ? $row : null;
}

	/** Obtiene varios productos por sus IDs (para validar carrito). */
	public static function obtenerPorIds(array $ids): array
	{
    $ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);
    if (empty($ids)) return [];

    // Placeholders dinámicos seguros: ?,?,?
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT p.id, p.nombre, p.precio, p.imagen_thumb,
                   c.nombre AS categoria_nombre
            FROM productos p
            INNER JOIN categorias c ON c.id = p.categoria_id
            WHERE p.id IN ($placeholders) AND p.activo = 1";

    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute($ids);
    return $stmt->fetchAll();
}
}
