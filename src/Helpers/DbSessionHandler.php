<?php
declare(strict_types=1);

final class DbSessionHandler implements SessionHandlerInterface
{
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }

    public function read(string $id): string|false
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT data FROM sessions WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row['data'] : '';
    }

    public function write(string $id, string $data): bool
    {
        $sql = "INSERT INTO sessions (id, data, last_activity, ip_address, user_agent)
                VALUES (:id, :data, :ts, :ip, :ua)
                ON DUPLICATE KEY UPDATE
                    data = VALUES(data),
                    last_activity = VALUES(last_activity),
                    ip_address = VALUES(ip_address),
                    user_agent = VALUES(user_agent)";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            ':id'   => $id,
            ':data' => $data,
            ':ts'   => time(),
            ':ip'   => $_SERVER['REMOTE_ADDR'] ?? null,
            ':ua'   => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);
    }

    public function destroy(string $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM sessions WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function gc(int $max_lifetime): int|false
    {
        $stmt = Database::getConnection()->prepare(
            "DELETE FROM sessions WHERE last_activity < :limit"
        );
        $stmt->execute([':limit' => time() - $max_lifetime]);
        return $stmt->rowCount();
    }

}
