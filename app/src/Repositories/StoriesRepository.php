<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\StoryEvent;
use PDO;

class StoriesRepository extends Repository
{
    private const EVENT_TYPE = 5;
    public function getAll(): array
    {
        $sql = "SELECT e.*, v.name AS venue_name, v.address AS venue_address, COALESCE(tt.price, 0.00) AS price
                FROM Event e
                JOIN Venue v ON v.venue_id = e.venue_id
                LEFT JOIN Ticket_Type tt ON tt.event_id = e.event_id AND tt.name = 'Regular Ticket'
                WHERE e.type = :type ORDER BY e.start_time ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type' => self::EVENT_TYPE]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, StoryEvent::class);
    }
    public function getBySlug(string $slug): ?StoryEvent
    {
        $sql = "SELECT e.*, v.name AS venue_name, v.address AS venue_address, COALESCE(tt.price, 0.00) AS price
                FROM Event e
                JOIN Venue v ON v.venue_id = e.venue_id
                LEFT JOIN Ticket_Type tt ON tt.event_id = e.event_id AND tt.name = 'Regular Ticket'
                WHERE e.type = :type AND e.slug = :slug LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type' => self::EVENT_TYPE, ':slug' => $slug]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, StoryEvent::class);
        return $stmt->fetch() ?: null;
    }
    public function getById(int $id): ?StoryEvent
    {
        $sql = "SELECT e.*, v.name AS venue_name, v.address AS venue_address, COALESCE(tt.price, 0.00) AS price
                FROM Event e
                JOIN Venue v ON v.venue_id = e.venue_id
                LEFT JOIN Ticket_Type tt ON tt.event_id = e.event_id AND tt.name = 'Regular Ticket'
                WHERE e.event_id = :id AND e.type = :type LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id, ':type' => self::EVENT_TYPE]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, StoryEvent::class);
        return $stmt->fetch() ?: null;
    }
    public function insert(array $data): int
    {
        $sql = "INSERT INTO Event (
                    name, slug, description, language, age_group, story_type, is_pay_as_you_like,
                    start_time, end_time, max_tickets, performer_name, performer_bio,
                    image_path, gallery_image_1, gallery_image_2,
                    audio_preview_path, audio_title, audio_transcript,
                    venue_id, type
                ) VALUES (
                    :name, :slug, :description, :language, :age_group, :story_type, :is_pay_as_you_like,
                    :start_time, :end_time, :max_tickets, :performer_name, :performer_bio,
                    :image_path, :gallery_image_1, :gallery_image_2,
                    :audio_preview_path, :audio_title, :audio_transcript,
                    :venue_id, :type
                )";
        
        $data['type'] = self::EVENT_TYPE;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        return (int) $this->connection->lastInsertId();
    }

    public function insertDefaultTicketTypes(int $eventId, bool $isPayAsYouLike): void
    {
        $sql = "INSERT INTO Ticket_Type (event_id, name, price, is_pay_as_you_like)
                VALUES
                (:event_id, 'Regular Ticket', 0.00, :is_pay_as_you_like),
                (:event_id2, 'HaarlemPas (25% off)', 0.00, 0)";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':event_id' => $eventId,
            ':event_id2' => $eventId,
            ':is_pay_as_you_like' => $isPayAsYouLike ? 1 : 0,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE Event SET 
                name = :name, slug = :slug, description = :description, language = :language, 
                age_group = :age_group, story_type = :story_type, is_pay_as_you_like = :is_pay_as_you_like, 
                start_time = :start_time, end_time = :end_time, max_tickets = :max_tickets, 
                performer_name = :performer_name, performer_bio = :performer_bio, image_path = :image_path,
                gallery_image_1 = :gallery_image_1, gallery_image_2 = :gallery_image_2,
                audio_preview_path = :audio_preview_path, audio_title = :audio_title, audio_transcript = :audio_transcript,
                venue_id = :venue_id
                WHERE event_id = :id AND type = :type";

        $data['id'] = $id;
        $data['type'] = self::EVENT_TYPE;
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Deletes a story event.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM Event WHERE event_id = :id AND type = :type";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':id' => $id, ':type' => self::EVENT_TYPE]);
    }

    /** Fetches ticket types (Regular, HaarlemPas) for a specific event */
    public function getTicketTypesForEvent(int $eventId): array
    {
        $sql = "SELECT type_id, name, price, is_pay_as_you_like, start_time, end_time
                FROM Ticket_Type
                WHERE event_id = :eid
                ORDER BY price DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':eid' => $eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetches CMS homepage content for stories */
    public function getHomepageContent(): ?array
    {
        $sql = "SELECT title, body_html, image_path FROM CMS_Content WHERE slug = 'stories' LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    
    /* Fetches all sessions that share the same event name.*/
    public function getScheduleByName(string $name): array
    {
        $sql = "SELECT event_id, start_time, end_time, language, slug
                FROM Event
                WHERE name = :name AND type = :type
                ORDER BY start_time ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':name' => $name, ':type' => self::EVENT_TYPE]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketTypesByEventId(int $eventId): array
    {
        $sql = "SELECT type_id, name, price, is_pay_as_you_like
                FROM Ticket_Type
                WHERE event_id = :event_id
                ORDER BY type_id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':event_id' => $eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTicketTypePrice(int $typeId, float $price): void
    {
        $sql = "UPDATE Ticket_Type
                SET price = :price
                WHERE type_id = :type_id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':price' => $price,
            ':type_id' => $typeId,
        ]);
    }
    
}