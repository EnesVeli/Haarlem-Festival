<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class OrderRepository extends Repository
{
    public function createOrder(int $userId, string $paymentMethod): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `Order` (user_id, status, payment_method)
             VALUES (:uid, 'pending', :method)"
        );
        $stmt->execute([':uid' => $userId, ':method' => $paymentMethod]);
        return (int) $this->connection->lastInsertId();
    }

    public function markPaid(int $orderId): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE `Order` SET status = 'paid' WHERE order_id = :id"
        );
        $stmt->execute([':id' => $orderId]);
    }

    public function addOrderItem(int $orderId, int $typeId, int $quantity, float $unitPrice): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `OrderItem` (order_id, type_id, quantity, unit_price)
             VALUES (:oid, :tid, :qty, :price)"
        );
        $stmt->execute([
            ':oid'   => $orderId,
            ':tid'   => $typeId,
            ':qty'   => $quantity,
            ':price' => $unitPrice,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function getOrderWithItems(int $orderId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT o.*, oi.item_id, oi.type_id, oi.quantity, oi.unit_price,
                    e.name AS event_name, e.start_time, e.end_time,
                    v.name AS venue_name
             FROM `Order` o
             JOIN `OrderItem` oi ON oi.order_id = o.order_id
             JOIN `Ticket_Type` tt ON tt.type_id = oi.type_id
             JOIN `Event` e ON e.event_id = tt.event_id
             JOIN `Venue` v ON v.venue_id = e.venue_id
             WHERE o.order_id = :id"
        );
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}