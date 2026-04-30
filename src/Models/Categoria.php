<?php
declare(strict_types=1);

final class Categoria
{
    /** Devuelve todas las categorías activas, ordenadas. */
    public static function listarActivas(): array
    {
        $sql = "SELECT id, nombre 
                FROM categorias 
                WHERE activo = 1 
                ORDER BY orden ASC, nombre ASC";

        $stmt = Database::getConnection()->query($sql);
        return $stmt->fetchAll();
    }
}
