<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class TicketRepository extends Repository
{
    public function createTicket(int $orderId, int $typeId, string $barcode): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `Ticket` (order_id, type_id, barcode, is_scanned)
             VALUES (:oid, :tid, :barcode, 0)"
        );
        $stmt->execute([
            ':oid'     => $orderId,
            ':tid'     => $typeId,
            ':barcode' => $barcode,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function getTicketsByOrder(int $orderId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT t.*, e.name AS event_name, e.start_time, e.end_time,
                    v.name AS venue_name, tt.name AS ticket_type_name
             FROM `Ticket` t
             JOIN `Ticket_Type` tt ON tt.type_id = t.type_id
             JOIN `Event` e ON e.event_id = tt.event_id
             JOIN `Venue` v ON v.venue_id = e.venue_id
             WHERE t.order_id = :oid"
        );
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}