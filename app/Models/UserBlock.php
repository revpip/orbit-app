<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;

final class UserBlock
{
    public static function block(int $blockerId, int $blockedUserId): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT IGNORE INTO user_blocks (blocker_id, blocked_user_id) VALUES (:blocker_id, :blocked_user_id)'
        );
        $stmt->execute([
            'blocker_id' => $blockerId,
            'blocked_user_id' => $blockedUserId,
        ]);
    }

    public static function isBlocked(int $viewerId, int $candidateId): bool
    {
        $stmt = Database::connection()->prepare(
            'SELECT 1 FROM user_blocks WHERE (blocker_id = :viewer_id AND blocked_user_id = :candidate_id) OR (blocker_id = :candidate_id_2 AND blocked_user_id = :viewer_id_2) LIMIT 1'
        );
        $stmt->execute([
            'viewer_id' => $viewerId,
            'candidate_id' => $candidateId,
            'candidate_id_2' => $candidateId,
            'viewer_id_2' => $viewerId,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
