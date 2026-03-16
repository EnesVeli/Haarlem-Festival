<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\StoryEvent;

class StoriesRepository extends Repository
{
    private const EVENT_TYPE = 5;

    public function getAll(): array
    {
        $sql = "SELECT
                    e.event_id,
                    e.name,
                    e.slug,
                    e.description,
                    e.language,
                    e.age_group,
                    e.story_type,
                    e.is_pay_as_you_like,
                    e.start_time,
                    e.end_time,
                    e.max_tickets,
                    e.performer_name,
                    e.performer_bio,
                    e.image_path,
                    v.name    AS venue_name,
                    v.address AS venue_address,
                    COALESCE(tt.price, 0.00) AS price
                FROM Event e
                JOIN  Venue v        ON v.venue_id  = e.venue_id
                LEFT JOIN Ticket_Type tt ON tt.event_id = e.event_id
                                       AND tt.name = 'Regular Ticket'
                WHERE e.type = :type
                ORDER BY e.start_time ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type' => self::EVENT_TYPE]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, StoryEvent::class);
    }

    public function getBySlug(string $slug): ?StoryEvent
    {
        $sql = "SELECT
                    e.event_id,
                    e.name,
                    e.slug,
                    e.description,
                    e.language,
                    e.age_group,
                    e.story_type,
                    e.is_pay_as_you_like,
                    e.start_time,
                    e.end_time,
                    e.max_tickets,
                    e.performer_name,
                    e.performer_bio,
                    e.image_path,
                    v.name    AS venue_name,
                    v.address AS venue_address,
                    COALESCE(tt.price, 0.00) AS price
                FROM Event e
                JOIN  Venue v        ON v.venue_id  = e.venue_id
                LEFT JOIN Ticket_Type tt ON tt.event_id = e.event_id
                                       AND tt.name = 'Regular Ticket'
                WHERE e.type = :type AND e.slug = :slug
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type' => self::EVENT_TYPE, ':slug' => $slug]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, StoryEvent::class);

        $result = $stmt->fetch();
        return $result ?: null;
    }
}
