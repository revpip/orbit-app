<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class Profile
{
    public static function findByUserId(int $userId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM profiles WHERE user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profile ?: null;
    }

    public static function save(int $userId, array $data): void
    {
        $existing = self::findByUserId($userId);

        $payload = [
            'user_id' => $userId,
            'headline' => trim((string) ($data['headline'] ?? '')),
            'bio' => trim((string) ($data['bio'] ?? '')),
            'date_of_birth' => $data['date_of_birth'] ?: null,
            'gender' => trim((string) ($data['gender'] ?? '')),
            'postcode_prefix' => strtoupper(trim((string) ($data['postcode_prefix'] ?? ''))),
            'town' => trim((string) ($data['town'] ?? '')),
            'country_code' => strtoupper(trim((string) ($data['country_code'] ?? 'GB'))),
            'visibility' => $data['visibility'] ?? 'members',
        ];

        if ($existing) {
            $stmt = Database::connection()->prepare(
                'UPDATE profiles SET headline = :headline, bio = :bio, date_of_birth = :date_of_birth, gender = :gender, postcode_prefix = :postcode_prefix, town = :town, country_code = :country_code, visibility = :visibility WHERE user_id = :user_id'
            );
            $stmt->execute($payload);
            return;
        }

        $stmt = Database::connection()->prepare(
            'INSERT INTO profiles (user_id, headline, bio, date_of_birth, gender, postcode_prefix, town, country_code, visibility) VALUES (:user_id, :headline, :bio, :date_of_birth, :gender, :postcode_prefix, :town, :country_code, :visibility)'
        );
        $stmt->execute($payload);
    }
}
