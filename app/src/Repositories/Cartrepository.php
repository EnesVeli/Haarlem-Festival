<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class CartRepository extends Repository
{
    // ── Read ────────────────────────────────────────────────────────────────

    public function getItemsByUser(int $userId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM `CartItem` WHERE user_id = :uid ORDER BY added_at DESC"
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

    // Find existing item for same user + event so we can increment instead of duplicate
    public function findExisting(int $userId, string $eventType, int $eventId, string $ticketType): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM `CartItem`
             WHERE user_id = :uid AND event_type = :et AND event_id = :eid AND ticket_type = :tt
             LIMIT 1"
        );
        $stmt->execute([
            'uid' => $userId,
            'et'  => $eventType,
            'eid' => $eventId,
            'tt'  => $ticketType,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ── Write ───────────────────────────────────────────────────────────────

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
}