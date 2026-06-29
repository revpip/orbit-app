<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => mb_strtolower(trim($email))]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function create(string $email, string $displayName, string $password): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (email, password_hash, display_name, status) VALUES (:email, :password_hash, :display_name, :status)'
        );

        $stmt->execute([
            'email' => mb_strtolower(trim($email)),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'display_name' => trim($displayName),
            'status' => 'active',
        ]);

        return (int) Database::connection()->lastInsertId();
    }
}
