<?php
declare(strict_types=1);

final class Cliente
{
    public static function porUsername(string $username): ?array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT id, nombre, username, password_hash, rol, activo,
                    intentos_login, ultimo_intento
             FROM clientes WHERE username = :u LIMIT 1"
        );
        $stmt->execute([':u' => $username]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function porId(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT id, nombre, username, rol, activo, curso_grupo
             FROM clientes WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function existeUsername(string $username): bool
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT 1 FROM clientes WHERE username = :u LIMIT 1"
        );
        $stmt->execute([':u' => $username]);
        return $stmt->fetch() !== false;
    }

    public static function crear(string $nombre, string $username, string $password, ?string $cursoGrupo): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = Database::getConnection()->prepare(
            "INSERT INTO clientes (nombre, username, password_hash, rol, curso_grupo, activo)
             VALUES (:n, :u, :p, 'cliente', :c, 1)"
        );
        $stmt->execute([
            ':n' => $nombre,
            ':u' => $username,
            ':p' => $hash,
            ':c' => $cursoGrupo,
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function registrarLoginOk(int $id): void
    {
        $stmt = Database::getConnection()->prepare(
            "UPDATE clientes
             SET ultimo_login = NOW(), intentos_login = 0, ultimo_intento = NULL
             WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
    }

    public static function registrarLoginFallido(int $id): void
    {
        $stmt = Database::getConnection()->prepare(
            "UPDATE clientes
             SET intentos_login = intentos_login + 1, ultimo_intento = NOW()
             WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
    }
}
