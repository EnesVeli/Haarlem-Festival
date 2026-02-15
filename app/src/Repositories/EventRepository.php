<?php

namespace App\Repositories;

use App\Framework\Repository;

class EventRepository extends Repository
{
    public function getActiveRecommendationsByKeys(array $keys): array
    {
        if (empty($keys)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($keys), '?'));

        $sql = "SELECT id, event_key, title, description, url
                FROM event_recommendations
                WHERE is_active = 1
                  AND event_key IN ($placeholders)
                ORDER BY sort_order ASC, id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($keys);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
