<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\FestivalTicket;
use PDO;

class TicketRepository extends Repository
{
    // ── Shared SELECT fragment ────────────────────────────────────────────────
    // festival_event_ticket_type_id stores Ticket_Type.type_id
    private const SELECT_TICKET = "
        SELECT
            t.festival_event_ticket_id,
            t.qr_token,
            t.ticket_code,
            t.is_scanned,
            t.scanned_at,
            e.name        AS title,
            tt.name       AS category,
            DATE(e.start_time) AS event_date,
            e.start_time  AS start_time,
            v.name        AS location
        FROM festival_event_tickets t
        JOIN Ticket_Type tt ON tt.type_id  = t.festival_event_ticket_type_id
        JOIN Event        e  ON e.event_id = tt.event_id
        JOIN Venue        v  ON v.venue_id = e.venue_id
    ";

    // ── Finders ───────────────────────────────────────────────────────────────

    public function findByTicketCode(string $ticketCode): ?FestivalTicket
    {
        $stmt = $this->connection->prepare(
            self::SELECT_TICKET . " WHERE t.ticket_code = :ticket_code LIMIT 1"
        );
        $stmt->execute(['ticket_code' => $ticketCode]);
        return $this->hydrate($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function findByQrToken(string $qrToken): ?FestivalTicket
    {
        $stmt = $this->connection->prepare(
            self::SELECT_TICKET . " WHERE t.qr_token = :qr_token LIMIT 1"
        );
        $stmt->execute(['qr_token' => $qrToken]);
        return $this->hydrate($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function findById(int $ticketId): ?FestivalTicket
    {
        $stmt = $this->connection->prepare(
            self::SELECT_TICKET . " WHERE t.festival_event_ticket_id = :ticket_id LIMIT 1"
        );
        $stmt->execute(['ticket_id' => $ticketId]);
        return $this->hydrate($stmt->fetch(PDO::FETCH_ASSOC));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    /**
     * @param int    $userId                    User who bought the ticket
     * @param int    $festivalEventTicketTypeId  Ticket_Type.type_id
     * @param string $qrToken                   Secure token for QR scanning
     * @param string $ticketCode                Human-readable code (e.g. HF-A1B2C3)
     */
    public function createTicket(
        int    $userId,
        int    $festivalEventTicketTypeId,
        string $qrToken,
        string $ticketCode
    ): int {
        $stmt = $this->connection->prepare("
            INSERT INTO festival_event_tickets
                (user_id, festival_event_ticket_type_id, qr_token, ticket_code, is_scanned)
            VALUES
                (:user_id, :type_id, :qr_token, :ticket_code, 0)
        ");

        $stmt->execute([
            'user_id'   => $userId,
            'type_id'   => $festivalEventTicketTypeId,
            'qr_token'  => $qrToken,
            'ticket_code' => $ticketCode,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    // ── Scan ──────────────────────────────────────────────────────────────────

    public function markAsScanned(int $ticketId): void
    {
        $stmt = $this->connection->prepare("
            UPDATE festival_event_tickets
            SET    is_scanned = 1, scanned_at = NOW()
            WHERE  festival_event_ticket_id = :ticket_id
        ");
        $stmt->execute(['ticket_id' => $ticketId]);
    }

    // ── Legacy order-based fetch (used by OrderService PDF generation) ────────

    public function getTicketsByOrder(int $orderId): array
    {
        $stmt = $this->connection->prepare("
            SELECT t.*, e.name AS event_name, e.start_time, e.end_time,
                   v.name AS venue_name, tt.name AS ticket_type_name
            FROM   Ticket t
            JOIN   Ticket_Type tt ON tt.type_id  = t.type_id
            JOIN   Event        e  ON e.event_id  = tt.event_id
            JOIN   Venue        v  ON v.venue_id  = e.venue_id
            WHERE  t.order_id = :oid
        ");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // ── Hydration ─────────────────────────────────────────────────────────────

    private function hydrate(array|false $row): ?FestivalTicket
    {
        if (!$row) {
            return null;
        }

        return new FestivalTicket(
            (int)  $row['festival_event_ticket_id'],
                   $row['qr_token'],
                   $row['ticket_code'],
            (int)  $row['is_scanned'],
                   $row['scanned_at'] ?? null,
                   $row['title'],
                   $row['category'],
                   $row['event_date'],
                   $row['start_time'],
                   $row['location']
        );
    }
}
