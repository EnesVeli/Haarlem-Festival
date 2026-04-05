<?php

namespace App\Services;

use App\Repositories\FestivalTicketRepository;

class FestivalTicketService
{
    private FestivalTicketRepository $repository;
    private TicketEmailService $ticketEmailService;

    public function __construct()
    {
        $this->repository = new FestivalTicketRepository();
        $this->ticketEmailService = new TicketEmailService();
    }

    public function createTicketAndSendEmail(
        int $userId,
        int $festivalEventTicketTypeId,
        string $customerEmail,
        string $customerName
    ): void {
        $qrToken = 'TICKET-' . bin2hex(random_bytes(8));

        $ticketId = $this->repository->createTicket(
            $userId,
            $festivalEventTicketTypeId,
            $qrToken
        );

        $ticket = [
            'festival_event_ticket_id' => $ticketId,
            'qr_token' => $qrToken
        ];

        $this->ticketEmailService->sendTicketEmail(
            $customerEmail,
            $customerName,
            $ticket
        );
    }
}