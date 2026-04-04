<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class NonStoriesTicketsRepository extends Repository
{
    /**
     * Returns all events for a non-stories category.
     *
     * @param int $eventType Event.type value (1=jazz, 2=dance, 3=history, 4=yummy)
     * @return array<int, array<string, mixed>>
     */
    public function getByType(int $eventType): array
    {
        $sql = "SELECT
                    e.event_id,
                    e.name,
                    e.slug,
                    e.description,
                    e.start_time,
                    e.end_time,
                    e.max_tickets,
                    e.is_pay_as_you_like,
                    v.name AS venue_name,
                    v.address AS venue_address,
                    tt.type_id AS ticket_type_id,
                    COALESCE(tt.price, 0.00) AS price
                FROM Event e
                JOIN Venue v
                  ON v.venue_id = e.venue_id
                LEFT JOIN Ticket_Type tt
                  ON tt.event_id = e.event_id
                 AND tt.name = 'Regular Ticket'
                WHERE e.type = :type
                ORDER BY e.start_time ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['type' => $eventType]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
