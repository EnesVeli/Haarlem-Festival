<?php

namespace App\Services;

use App\Exceptions\TicketScanException;
use App\Interfaces\Services\ITicketScanService;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use chillerlan\QRCode\QRCode;
use DateTime;

class TicketScanService implements ITicketScanService
{
    private static ?TicketScanService $_instance = null;

    public static function getInstance() : TicketScanService {
        if(self::$_instance === null) self::$_instance = new TicketScanService(TicketRepository::getInstance(), OrderService::getInstance());

        return self::$_instance;
    }

    private TicketRepository $repository;
    private OrderService $order_service;

    private function __construct(TicketRepository $repository, OrderService $order_service)
    {
        $this->repository = $repository;
        $this->order_service = $order_service;
    }

    public function scanTicket(string $scan_value): Ticket
    {
        $scan_value = trim($scan_value);

        $ticket = $this->repository->findByQrToken($scan_value);

        if (!$ticket) {
            throw new TicketScanException('Ticket not found.');
        }

        if ($ticket->scanned_at !== null) {
            throw new TicketScanException('This ticket was already scanned.');
        }

        $this->repository->markAsScanned($ticket->ticket_id);
        $ticket->scanned_at = new DateTime();

        $ticket->order_item = $this->order_service->getOrderItemWithBooking($ticket->item_id);

        return $ticket;
    }

    public function getTicketQr(int $ticketId): ?string
    {
        $ticket = $this->repository->findById($ticketId);
        if ($ticket === null) return null;
        return (new QRCode())->render($ticket->qr_token);
    }
}