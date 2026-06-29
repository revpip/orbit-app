<?php

declare(strict_types=1);

namespace Orbit\Models;

use Orbit\Core\Database;
use PDO;

final class Intent
{
    public static function active(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM intents WHERE is_active = 1 ORDER BY category, label');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function forUser(int $userId): array
    {
        $stmt = Database::connection()->prepare('SELECT intent_id FROM user_intents WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return array_map('intval', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'intent_id'));
    }

    public static function syncForUser(int $userId, array $intentIds): void
    {
        $db = Database::connection();
        $db->beginTransaction();

        $delete = $db->prepare('DELETE FROM user_intents WHERE user_id = :user_id');
        $delete->execute(['user_id' => $userId]);

        $insert = $db->prepare('INSERT INTO user_intents (user_id, intent_id) VALUES (:user_id, :intent_id)');

        foreach (array_unique(array_map('intval', $intentIds)) as $intentId) {
            if ($intentId > 0) {
                $insert->execute(['user_id' => $userId, 'intent_id' => $intentId]);
            }
        }

        $db->commit();
    }
}
