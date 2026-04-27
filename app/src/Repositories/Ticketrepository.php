<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\Ticket;
use PDO;

class TicketRepository extends Repository
{
    private static ?TicketRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : TicketRepository {
        if(self::$_instance === null) self::$_instance = new TicketRepository();

        return self::$_instance;
    }

    public function findByTicketCode(string $code): ?Ticket
    {
        $stmt = $this->connection->prepare("SELECT `ticket_id`, `item_id`, `qr_token`, `code`, `scanned_at` AS `scanned_at_` FROM `Tickets` WHERE `code` = :code LIMIT 1;");
        $stmt->bindValue('code', $code, PDO::PARAM_STR);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Ticket::class);
        $res = $stmt->fetch();

        return $res === false ? null : $res;
    }

    public function findByQrToken(string $qr_token): ?Ticket
    {
        $stmt = $this->connection->prepare("SELECT `ticket_id`, `item_id`, `qr_token`, `code`, `scanned_at` AS `scanned_at_` FROM `Tickets` WHERE `qr_token` = :qr_token LIMIT 1;");
        $stmt->bindValue('qr_token', $qr_token, PDO::PARAM_STR);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Ticket::class);
        $res = $stmt->fetch();

        return $res === false ? null : $res;
    }

    public function findById(int $ticket_id): ?Ticket
    {
        $stmt = $this->connection->prepare("SELECT `ticket_id`, `item_id`, `qr_token`, `code`, `scanned_at` AS `scanned_at_` FROM `Tickets` WHERE `ticket_id` = :ticket_id LIMIT 1;");
        $stmt->bindValue('ticket_id', $ticket_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Ticket::class);
        $res = $stmt->fetch();

        return $res === false ? null : $res;
    }

    public function createTicket(Ticket $ticket): ?int {
        $stmt = $this->connection->prepare("INSERT INTO `Tickets`(`item_id`, `qr_token`, `code`, `scanned_at`) VALUES (:item_id, :qr_token, :code, NULL);");

        $stmt->bindValue('item_id', $ticket->item_id, PDO::PARAM_INT);
        $stmt->bindValue('qr_token', $ticket->qr_token, PDO::PARAM_STR);
        $stmt->bindValue('code', $ticket->code, PDO::PARAM_STR);

        $res = $stmt->execute();

        if($res === false) return null;

        return $this->connection->lastInsertId();
    }

    public function markAsScanned(int $ticket_id) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `Tickets` SET `scanned_at`=NOW() WHERE `ticket_id` = :ticket_id;");

        $stmt->bindValue('ticket_id', $ticket_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
