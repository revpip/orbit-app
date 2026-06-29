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
            'SELECT u.id AS user_id, u.display_name, p.headline, p.bio, p.town, p.postcode_prefix, pp.*
             FROM users u
             INNER JOIN profiles p ON p.user_id = u.id
             INNER JOIN psychology_profiles pp ON pp.user_id = u.id
             WHERE u.id <> :user_id AND u.status = "active"
             ORDER BY u.created_at DESC
             LIMIT 50'
        );
        $stmt->execute(['user_id' => $userId]);

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
