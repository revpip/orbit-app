<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class PsychologyProfile
{
    public static function findByUserId(int $userId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM psychology_profiles WHERE user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profile ?: null;
    }

    public static function save(int $userId, array $data): void
    {
        $payload = [
            'user_id' => $userId,
            'communication_style' => (string) ($data['communication_style'] ?? ''),
            'social_energy' => (string) ($data['social_energy'] ?? ''),
            'conflict_style' => (string) ($data['conflict_style'] ?? ''),
            'humour_style' => (string) ($data['humour_style'] ?? ''),
            'reliability_self_score' => (int) ($data['reliability_self_score'] ?? 5),
            'openness_score' => (int) ($data['openness_score'] ?? 5),
            'boundaries_score' => (int) ($data['boundaries_score'] ?? 5),
            'raw_answers' => json_encode($data, JSON_THROW_ON_ERROR),
        ];

        $stmt = Database::connection()->prepare(
            'INSERT INTO psychology_profiles (user_id, communication_style, social_energy, conflict_style, humour_style, reliability_self_score, openness_score, boundaries_score, raw_answers)
             VALUES (:user_id, :communication_style, :social_energy, :conflict_style, :humour_style, :reliability_self_score, :openness_score, :boundaries_score, :raw_answers)
             ON DUPLICATE KEY UPDATE communication_style = VALUES(communication_style), social_energy = VALUES(social_energy), conflict_style = VALUES(conflict_style), humour_style = VALUES(humour_style), reliability_self_score = VALUES(reliability_self_score), openness_score = VALUES(openness_score), boundaries_score = VALUES(boundaries_score), raw_answers = VALUES(raw_answers)'
        );

        $stmt->execute($payload);
    }
}
