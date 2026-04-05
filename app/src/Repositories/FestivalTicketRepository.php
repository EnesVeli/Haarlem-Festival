<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class FestivalTicketRepository extends Repository
{
    public function findByQrToken(string $qrToken): ?array
    {
        $sql = "
            SELECT 
                t.festival_event_ticket_id,
                t.qr_token,
                t.is_scanned,
                t.scanned_at,
                e.title,
                e.category,
                e.event_date,
                e.start_time,
                e.location
            FROM festival_event_tickets t
            JOIN festival_event_ticket_types tt
                ON t.festival_event_ticket_type_id = tt.festival_event_ticket_type_id
            JOIN festival_events e
                ON tt.festival_event_id = e.festival_event_id
            WHERE t.qr_token = :qr_token
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['qr_token' => $qrToken]);

        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ticket ?: null;
    }

    public function markAsScanned(int $ticketId): void
    {
        $sql = "
            UPDATE festival_event_tickets
            SET is_scanned = 1,
                scanned_at = NOW()
            WHERE festival_event_ticket_id = :ticket_id
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['ticket_id' => $ticketId]);
    }
    public function findById(int $ticketId): ?array
{
    $sql = "
        SELECT festival_event_ticket_id, qr_token
        FROM festival_event_tickets
        WHERE festival_event_ticket_id = :ticket_id
        LIMIT 1
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->execute(['ticket_id' => $ticketId]);

    $ticket = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $ticket ?: null;
}
public function createTicket(
    int $userId,
    int $festivalEventTicketTypeId,
    string $qrToken
): int {
    $sql = "
        INSERT INTO festival_event_tickets
        (user_id, festival_event_ticket_type_id, qr_token, is_scanned)
        VALUES
        (:user_id, :festival_event_ticket_type_id, :qr_token, 0)
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'festival_event_ticket_type_id' => $festivalEventTicketTypeId,
        'qr_token' => $qrToken,
    ]);

    return (int)$this->connection->lastInsertId();
}
}