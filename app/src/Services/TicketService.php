<?php

namespace App\Services;

use App\Models\FestivalTicket;
use App\Repositories\TicketRepository;
use chillerlan\QRCode\QRCode;

class TicketService
{
    private TicketRepository $repository;
    private MailService $mailService;

    public function __construct()
    {
        $this->repository = new TicketRepository();
        $this->mailService = new MailService();
    }

    public function createTicketAndSendEmail(
        int $userId,
        int $festivalEventTicketTypeId,
        string $customerEmail,
        string $customerName
    ): void {
        // Generate secure QR token (used for scanning)
        $qrToken = bin2hex(random_bytes(24));
        // Generate short readable code (for user)
        $ticketCode = 'HF-' . strtoupper(bin2hex(random_bytes(3)));

        // Save ticket
        $ticketId = $this->repository->createTicket(
            $userId,
            $festivalEventTicketTypeId,
            $qrToken,
            $ticketCode
        );

        // Reload as model
        $ticket = $this->repository->findById($ticketId);

        if (!$ticket) return;

        $subject = 'Your Haarlem Festival Ticket';
        $html = $this->buildTicketEmailHtml($customerName, $ticket);
        $altText = $this->buildTicketEmailText($ticket);

        $this->mailService->sendHTMLMail(
            $customerEmail,
            $customerName,
            $subject,
            $html,
            $altText
        );
    }

    private function buildTicketEmailHtml(string $customerName, FestivalTicket $ticket): string
    {
        $qrImage = (new QRCode())->render($ticket->qrToken);

        return '
            <h2>Your Ticket</h2>
            <p>Hello ' . htmlspecialchars($customerName) . ',</p>
            <p>Here is your ticket QR code:</p>
            <p>
                <img src="' . $qrImage . '" alt="Ticket QR Code" style="width:250px;height:250px;">
            </p>
            <p><strong>Ticket code:</strong> ' . htmlspecialchars($ticket->ticketCode) . '</p>
        ';
    }

    private function buildTicketEmailText(FestivalTicket $ticket): string
    {
        return 'Your ticket code: ' . $ticket->ticketCode;
    }
}