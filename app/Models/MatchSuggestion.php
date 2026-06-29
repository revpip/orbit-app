<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use Orbit\Services\CompatibilityService;
use PDO;

final class MatchSuggestion
{
    public static function forUser(int $userId, int $limit = 10): array
    {
        $current = PsychologyProfile::findByUserId($userId);

        if (!$current) {
            return [];
        }

        $stmt = Database::connection()->prepare(
            'SELECT u.id AS user_id, u.display_name, p.headline, p.bio, p.town, p.postcode_prefix, pp.*, COALESCE(ts.score, 50) AS trust_score
             FROM users u
             INNER JOIN profiles p ON p.user_id = u.id
             INNER JOIN psychology_profiles pp ON pp.user_id = u.id
             LEFT JOIN trust_scores ts ON ts.user_id = u.id
             WHERE u.id <> :user_id
               AND u.status = "active"
               AND NOT EXISTS (
                    SELECT 1 FROM user_blocks b
                    WHERE (b.blocker_id = :user_id_block_a AND b.blocked_user_id = u.id)
                       OR (b.blocker_id = u.id AND b.blocked_user_id = :user_id_block_b)
               )
             ORDER BY u.created_at DESC
             LIMIT 50'
        );
        $stmt->execute([
            'user_id' => $userId,
            'user_id_block_a' => $userId,
            'user_id_block_b' => $userId,
        ]);

        $service = new CompatibilityService();
        $matches = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $candidate) {
            $result = $service->score($current, $candidate);
            $candidate['compatibility_score'] = $result['score'];
            $candidate['reason_summary'] = $result['reason'];
            $matches[] = $candidate;
        }

        usort($matches, static fn (array $a, array $b): int => $b['compatibility_score'] <=> $a['compatibility_score']);

        return array_slice($matches, 0, $limit);
    }
}
