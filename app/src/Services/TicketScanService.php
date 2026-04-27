<?php

namespace App\Services;

use App\Exceptions\TicketScanException;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use DateTime;

class TicketScanService
{
    private TicketRepository $repository;
    private OrderService $order_service;

    public function __construct()
    {
        $this->repository = new TicketRepository();
        $this->order_service = new OrderService();
    }

    public function scanTicket(string $scan_value): Ticket
    {
        //$q = $this->repository->findById(25); $q->order_item = $this->order_service->getOrderItemWithBooking($q->item_id); return $q;

        $scan_value = trim($scan_value);

        $ticket = $this->repository->findByQrToken($scan_value);  
        //$ticket = $this->repository->findByTicketCode($scan_value);

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
}