<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class Report
{
    public static function create(int $reporterId, int $reportedUserId, string $reason, string $details): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO reports (reporter_id, reported_user_id, reason, details) VALUES (:reporter_id, :reported_user_id, :reason, :details)'
        );
        $stmt->execute([
            'reporter_id' => $reporterId,
            'reported_user_id' => $reportedUserId,
            'reason' => trim($reason),
            'details' => trim($details),
        ]);

        TrustScore::recalculate($reportedUserId);
    }

    public static function openReports(): array
    {
        $stmt = Database::connection()->query(
            'SELECT r.*, reporter.display_name AS reporter_name, reported.display_name AS reported_name
             FROM reports r
             INNER JOIN users reporter ON reporter.id = r.reporter_id
             INNER JOIN users reported ON reported.id = r.reported_user_id
             WHERE r.status IN ("open", "reviewing")
             ORDER BY r.created_at DESC
             LIMIT 100'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
