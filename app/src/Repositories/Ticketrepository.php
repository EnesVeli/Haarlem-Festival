<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\FestivalTicket;
use PDO;

class TicketRepository extends Repository
{
    public function findByTicketCode(string $ticketCode): ?FestivalTicket
{
    $stmt = $this->connection->prepare("
        SELECT 
            t.festival_event_ticket_id,
            t.qr_token,
            t.ticket_code,
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
        WHERE t.ticket_code = :ticket_code
        LIMIT 1
    ");

    $stmt->execute(['ticket_code' => $ticketCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return null;

    return new FestivalTicket(
        (int)$row['festival_event_ticket_id'],
        $row['qr_token'],
        $row['ticket_code'],
        (int)$row['is_scanned'],
        $row['scanned_at'] ?? null,
        $row['title'],
        $row['category'],
        $row['event_date'],
        $row['start_time'],
        $row['location']
    );
}
    public function findByQrToken(string $qrToken): ?FestivalTicket
    {
        $stmt = $this->connection->prepare("
            SELECT 
                t.festival_event_ticket_id,
                t.qr_token,
                t.ticket_code,
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
        ");

        $stmt->execute(['qr_token' => $qrToken]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new FestivalTicket(
            (int)$row['festival_event_ticket_id'],
            $row['qr_token'],
            $row['ticket_code'],
            (int)$row['is_scanned'],
            $row['scanned_at'] ?? null,
            $row['title'],
            $row['category'],
            $row['event_date'],
            $row['start_time'],
            $row['location']
        );
    }

    public function findById(int $ticketId): ?FestivalTicket
    {
        $stmt = $this->connection->prepare("
            SELECT 
                t.festival_event_ticket_id,
                t.qr_token,
                t.ticket_code,
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
            WHERE t.festival_event_ticket_id = :ticket_id
            LIMIT 1
        ");

        $stmt->execute(['ticket_id' => $ticketId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new FestivalTicket(
            (int)$row['festival_event_ticket_id'],
            $row['qr_token'],
            $row['ticket_code'],
            (int)$row['is_scanned'],
            $row['scanned_at'] ?? null,
            $row['title'],
            $row['category'],
            $row['event_date'],
            $row['start_time'],
            $row['location']
        );
    }

    public function createTicket(
        int $userId,
        int $festivalEventTicketTypeId,
        string $qrToken,
        string $ticketCode
    ): int {
        $stmt = $this->connection->prepare("
            INSERT INTO festival_event_tickets (
                user_id,
                festival_event_ticket_type_id,
                qr_token,
                ticket_code,
                is_scanned
            )
            VALUES (
                :user_id,
                :festival_event_ticket_type_id,
                :qr_token,
                :ticket_code,
                0
            )
        ");

        $stmt->execute([
            'user_id' => $userId,
            'festival_event_ticket_type_id' => $festivalEventTicketTypeId,
            'qr_token' => $qrToken,
            'ticket_code' => $ticketCode,
        ]);

        return (int)$this->connection->lastInsertId();
    }

    public function markAsScanned(int $ticketId): void
    {
        $stmt = $this->connection->prepare("
            UPDATE festival_event_tickets
            SET is_scanned = 1,
                scanned_at = NOW()
            WHERE festival_event_ticket_id = :ticket_id
        ");

        $stmt->execute(['ticket_id' => $ticketId]);
    }
}