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
        return self::forUser($userId);
    }

    public static function createDefault(int $userId): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT IGNORE INTO trust_scores (user_id, score, verification_score, behaviour_score, reliability_score, flag_penalty) VALUES (:user_id, 50, 0, 50, 50, 0)'
        );
        $stmt->execute(['user_id' => $userId]);
    }

    public static function recalculate(int $userId): void
    {
        $flagCountStmt = Database::connection()->prepare('SELECT COUNT(*) FROM user_flags WHERE target_user_id = :user_id AND status IN ("open", "reviewing")');
        $flagCountStmt->execute(['user_id' => $userId]);
        $flagPenalty = min(40, ((int) $flagCountStmt->fetchColumn()) * 10);

        $verificationStmt = Database::connection()->prepare('SELECT COUNT(*) FROM verifications WHERE user_id = :user_id AND status = "approved"');
        $verificationStmt->execute(['user_id' => $userId]);
        $verificationScore = min(30, ((int) $verificationStmt->fetchColumn()) * 10);

        $score = max(0, min(100, 50 + $verificationScore - $flagPenalty));

        $stmt = Database::connection()->prepare(
            'INSERT INTO trust_scores (user_id, score, verification_score, behaviour_score, reliability_score, flag_penalty)
             VALUES (:user_id, :score, :verification_score, 50, 50, :flag_penalty)
             ON DUPLICATE KEY UPDATE score = VALUES(score), verification_score = VALUES(verification_score), flag_penalty = VALUES(flag_penalty)'
        );
        $stmt->execute([
            'user_id' => $userId,
            'score' => $score,
            'verification_score' => $verificationScore,
            'flag_penalty' => $flagPenalty,
        ]);
    }
}
