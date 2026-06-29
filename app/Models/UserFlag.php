<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class UserFlag
{
    public static function create(int $creatorId, int $targetUserId, string $reason, string $details = ''): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO user_flags (created_by_user_id, target_user_id, reason, details) VALUES (:creator_id, :target_id, :reason, :details)'
        );
        $stmt->execute([
            'creator_id' => $creatorId,
            'target_id' => $targetUserId,
            'reason' => trim($reason),
            'details' => trim($details),
        ]);

        TrustScore::recalculate($targetUserId);
    }

    public static function open(int $limit = 50): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT f.*, reporter.display_name AS reporter_name, target.display_name AS target_name
             FROM user_flags f
             INNER JOIN users reporter ON reporter.id = f.created_by_user_id
             INNER JOIN users target ON target.id = f.target_user_id
             WHERE f.status IN ("open", "reviewing")
             ORDER BY f.created_at ASC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
