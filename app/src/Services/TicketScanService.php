<?php

namespace App\Services;

use App\Exceptions\TicketScanException;
use App\Models\FestivalTicket;
use App\Repositories\TicketRepository;

class TicketScanService
{
    private TicketRepository $repository;

    public function __construct()
    {
        $this->repository = new TicketRepository();
    }

    public function scanTicket(string $scanValue): FestivalTicket
    {
        $scanValue = trim($scanValue);

        $ticket = $this->repository->findByQrToken($scanValue);

        if (!$ticket) {
            $ticket = $this->repository->findByTicketCode($scanValue);
        }

        if (!$ticket) {
            throw new TicketScanException('Ticket not found.');
        }

        if ($ticket->isScanned === 1) {
            throw new TicketScanException('This ticket was already scanned.');
        }

        $this->repository->markAsScanned($ticket->festivalEventTicketId);
        $ticket->isScanned = 1;

        return $ticket;
    }
}