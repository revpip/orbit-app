<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class TrustScore
{
    public static function forUser(int $userId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM trust_scores WHERE user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        $score = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($score) {
            return $score;
        }

        self::createDefault($userId);

        return [
            'user_id' => $userId,
            'score' => 50,
            'verification_score' => 0,
            'behaviour_score' => 50,
            'reliability_score' => 50,
            'report_penalty' => 0,
        ];
    }

    public static function createDefault(int $userId): void
    {
        $stmt = Database::connection()->prepare('INSERT IGNORE INTO trust_scores (user_id) VALUES (:user_id)');
        $stmt->execute(['user_id' => $userId]);
    }

    public static function applyReportPenalty(int $userId, int $penalty = 5): void
    {
        self::createDefault($userId);

        $stmt = Database::connection()->prepare(
            'UPDATE trust_scores SET report_penalty = LEAST(100, report_penalty + :penalty), score = GREATEST(0, score - :penalty) WHERE user_id = :user_id'
        );
        $stmt->execute(['user_id' => $userId, 'penalty' => $penalty]);
    }
}
