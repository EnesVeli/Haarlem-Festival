<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\StoryEvent;
use PDO;

class StoriesRepository extends Repository
{
    public function getAll(): array
    {
        $sql = "SELECT `event_id`, `name`, `slug`, `address_name`, `address_text`, `description`, `performer_name`, `performer_bio`, `language`,
            `age_group`, `story_type`, `is_pay_as_you_like`, `start_time`, `end_time`, `max_tickets`, `image_path`, `gallery_image_1`,
            `gallery_image_2` FROM `StoryEvents` ORDER BY `start_time` ASC;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, StoryEvent::class);
    }
    public function getBySlug(string $slug): ?StoryEvent
    {
        $sql = "SELECT `event_id`, `name`, `slug`, `address_name`, `address_text`, `description`, `performer_name`, `performer_bio`, `language`,
            `age_group`, `story_type`, `is_pay_as_you_like`, `start_time`, `end_time`, `max_tickets`, `image_path`, `gallery_image_1`,
            `gallery_image_2` FROM `StoryEvents` WHERE `slug` = :slug LIMIT 1;";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([':slug' => $slug]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, StoryEvent::class);     
        return $stmt->fetch() ?: null;
    }
    public function getById(int $event_id): ?StoryEvent
    {
        $sql = "SELECT `event_id`, `name`, `slug`, `address_name`, `address_text`, `description`, `performer_name`, `performer_bio`, `language`,
            `age_group`, `story_type`, `is_pay_as_you_like`, `start_time`, `end_time`, `max_tickets`, `image_path`, `gallery_image_1`,
            `gallery_image_2` FROM `StoryEvents` WHERE `event_id` = :event_id LIMIT 1;";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([':event_id' => $event_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, StoryEvent::class);
        return $stmt->fetch() ?: null;
    }
    public function insert(StoryEvent $event): int
    {
        $sql = "INSERT INTO `StoryEvents`(`name`, `slug`, `address_name`, `address_text`, `description`, `performer_name`, `performer_bio`,
            `language`, `age_group`, `story_type`, `is_pay_as_you_like`, `start_time`, `end_time`, `max_tickets`, `image_path`, `gallery_image_1`,
            `gallery_image_2`) VALUES (
                    :name, :slug, :address_name, :address_text, :description, :performer_name, :performer_bio, :language, :age_group, :story_type,
                    :is_pay_as_you_like, :start_time, :end_time, :max_tickets, :image_path, :gallery_image_1, :gallery_image_2);";
        
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam('name', $event->name, PDO::PARAM_INT);
        $stmt->bindParam('slug', $event->slug, PDO::PARAM_STR);
        $stmt->bindParam('address_name', $event->address_name, PDO::PARAM_STR);
        $stmt->bindParam('address_text', $event->address_text, PDO::PARAM_STR);
        $stmt->bindParam('description', $event->description, PDO::PARAM_STR);
        $stmt->bindParam('performer_name', $event->performer_name, PDO::PARAM_STR);
        $stmt->bindParam('performer_bio', $event->performer_bio, PDO::PARAM_STR);
        $stmt->bindParam('language', $event->language, PDO::PARAM_STR);
        $stmt->bindParam('age_group', $event->age_group, PDO::PARAM_STR);
        $stmt->bindParam('story_type', $event->story_type, PDO::PARAM_STR);
        $stmt->bindParam('is_pay_as_you_like', $event->is_pay_as_you_like, PDO::PARAM_INT);
        $stmt->bindParam('start_time', $event->start_time, PDO::PARAM_STR);
        $stmt->bindParam('end_time', $event->end_time, PDO::PARAM_STR);
        $stmt->bindParam('max_tickets', $event->max_tickets, PDO::PARAM_INT);
        $stmt->bindParam('image_path', $event->image_path, PDO::PARAM_STR);
        $stmt->bindParam('gallery_image_1', $event->gallery_image_1, PDO::PARAM_STR);
        $stmt->bindParam('gallery_image_2', $event->gallery_image_2, PDO::PARAM_STR);

        $stmt->execute();

        return (int) $this->connection->lastInsertId();
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
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Deletes a story event.
     * @param int $event_id
     * @return bool
     */
    public function delete(int $event_id): bool
    {
        $sql = "DELETE FROM `StoryEvents` WHERE `event_id` = :event_id;";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([':event_id' => $event_id]);
    }

    /** Fetches CMS homepage content for stories */
    /**
     * @deprecated Use StoriesHomepageRepository::getBySlug() instead.
     */
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
        $sql = "SELECT `event_id`, `start_time`, `end_time`, `language`, `slug` FROM `StoryEvents`
                WHERE `name` = :name ORDER BY `start_time` ASC;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':name' => $name]);

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
