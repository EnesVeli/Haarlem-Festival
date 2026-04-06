<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class CartRepository extends Repository
{
    public function getItemsByUser(int $userId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT ci.*, e.name AS event_name, e.image_path AS event_image,
                    e.start_time AS event_start, e.end_time AS event_end,
                    v.name AS venue_name
             FROM `CartItem` ci
             LEFT JOIN `Event` e ON e.event_id = ci.event_id
             LEFT JOIN `Venue` v ON v.venue_id = e.venue_id
             WHERE ci.user_id = :uid
             ORDER BY ci.added_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById(int $cartItemId): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM `CartItem` WHERE cart_item_id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $cartItemId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findExisting(int $userId, string $eventType, int $eventId, string $ticketType): ?array
    {
        if ($eventType === 'stories') {
            $stmt = $this->connection->prepare(
                "SELECT * FROM `CartItem`
                 WHERE user_id = :uid
                   AND event_type IN ('stories', 'story')
                   AND event_id = :eid
                   AND ticket_type = :tt
                 LIMIT 1"
            );
            $stmt->execute([
                'uid' => $userId,
                'eid' => $eventId,
                'tt'  => $ticketType,
            ]);
        } else {
            $stmt = $this->connection->prepare(
                "SELECT * FROM `CartItem`
                 WHERE user_id = :uid
                   AND event_type = :et
                   AND event_id = :eid
                   AND ticket_type = :tt
                 LIMIT 1"
            );
            $stmt->execute([
                'uid' => $userId,
                'et'  => $eventType,
                'eid' => $eventId,
                'tt'  => $ticketType,
            ]);
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function addItem(int $userId, string $eventType, int $eventId, string $ticketType, int $quantity, float $price): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `CartItem` (user_id, event_type, event_id, ticket_type, quantity, price)
             VALUES (:uid, :et, :eid, :tt, :qty, :price)"
        );
        $stmt->execute([
            'uid'   => $userId,
            'et'    => $eventType,
            'eid'   => $eventId,
            'tt'    => $ticketType,
            'qty'   => $quantity,
            'price' => $price,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE `CartItem` SET quantity = :qty WHERE cart_item_id = :id"
        );
        $stmt->execute(['qty' => $quantity, 'id' => $cartItemId]);
    }

    public function removeItem(int $cartItemId): void
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM `CartItem` WHERE cart_item_id = :id"
        );
        $stmt->execute(['id' => $cartItemId]);
    }

    public function clearCart(int $userId): void
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM `CartItem` WHERE user_id = :uid"
        );
        $stmt->execute(['uid' => $userId]);
    }

    public function countItemsForUser(int $userId): int
    {
        $stmt = $this->connection->prepare(
            "SELECT COALESCE(SUM(quantity), 0) FROM `CartItem` WHERE user_id = :uid"
        );
        $stmt->execute(['uid' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    public function getTicketTypeById(int $ticketTypeId): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT type_id, event_id, name, price, is_pay_as_you_like
             FROM `Ticket_Type`
             WHERE type_id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $ticketTypeId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function getEventTypeCodeByEventId(int $eventId): ?int
    {
        $stmt = $this->connection->prepare(
            "SELECT type
             FROM `Event`
             WHERE event_id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $eventId]);
        $value = $stmt->fetchColumn();

        return $value === false ? null : (int) $value;
    }

    public function getMaxTicketsForEvent(int $eventId): ?int
    {
        $stmt = $this->connection->prepare(
            "SELECT max_tickets
             FROM `Event`
             WHERE event_id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $eventId]);
        $value = $stmt->fetchColumn();

        return $value === false ? null : (int) $value;
    }

    public function getReservedCartQuantityByEvent(int $eventId): int
    {
        $stmt = $this->connection->prepare(
            "SELECT COALESCE(SUM(quantity), 0)
             FROM `CartItem`
             WHERE event_id = :id"
        );
        $stmt->execute(['id' => $eventId]);

        return (int) $stmt->fetchColumn();
    }

    public function getPaidTicketQuantityByEvent(int $eventId): int
    {
        $stmt = $this->connection->prepare(
            "SELECT COALESCE(SUM(oi.quantity), 0)
             FROM `OrderItem` oi
             JOIN `Ticket_Type` tt ON tt.type_id = oi.type_id
             JOIN `Order` o ON o.order_id = oi.order_id
             WHERE tt.event_id = :id
               AND o.status = 'paid'"
        );
        $stmt->execute(['id' => $eventId]);

        return (int) $stmt->fetchColumn();
    }
}
