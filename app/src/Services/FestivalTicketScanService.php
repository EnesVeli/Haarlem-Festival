<?php

namespace App\Services;

use App\Repositories\FestivalTicketRepository;

class FestivalTicketScanService
{
    private FestivalTicketRepository $repository;

    public function __construct()
    {
        $this->repository = new FestivalTicketRepository();
    }

    public function scanTicket(string $qrToken): array
    {
        $ticket = $this->repository->findByQrToken($qrToken);

        if (!$ticket) {
            return [
                'status' => 'error',
                'message' => 'Ticket not found.'
            ];
        }

        if ((int)$ticket['is_scanned'] === 1) {
            return [
                'status' => 'warning',
                'message' => 'This ticket was already scanned.',
                'ticket' => $ticket
            ];
        }

        $this->repository->markAsScanned((int)$ticket['festival_event_ticket_id']);
        $ticket['is_scanned'] = 1;

        return [
            'status' => 'success',
            'message' => 'Ticket is valid. Entry allowed.',
            'ticket' => $ticket
        ];
        //throw an exception
        // have a ticket model, retyrn a model
    }
}