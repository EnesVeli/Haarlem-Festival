<?php

namespace App\Repositories;

use App\Framework\Repository;

class EventRepository extends Repository
{
    public function getHomepageEvents(): array
    {
        // For now static → later DB
        return [
            [
                'slug' => 'jazz',
                'title' => 'Haarlem Jazz',
                'category' => 'Music',
                'description' => 'World-class jazz performances across the city.'
            ],
            [
                'slug' => 'dance',
                'title' => 'DANCE!',
                'category' => 'Music',
                'description' => 'Three nights of house, techno and trance.'
            ],
            [
                'slug' => 'yummy',
                'title' => 'Yummy!',
                'category' => 'Food',
                'description' => 'Exclusive festival menus from top restaurants.'
            ],
            [
                'slug' => 'history',
                'title' => 'A Stroll Through History',
                'category' => 'Culture',
                'description' => 'Guided walks through Haarlem’s past.'
            ],
            [
                'slug' => 'stories',
                'title' => 'Stories in Haarlem',
                'category' => 'Culture',
                'description' => 'Live storytelling across the city.'
            ],
        ];
    }
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
