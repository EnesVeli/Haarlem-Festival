<?php

namespace App\Controllers;

use App\Repositories\TicketRepository;
use App\ViewModels\TicketScanner\TicketQrViewModel;
use chillerlan\QRCode\QRCode;

class TicketQrController
{
    private TicketRepository $repository;

    public function __construct()
    {
        $this->repository = new TicketRepository();
    }

    public function show(array $vars): void
    {
        $ticketId = (int) ($vars['id'] ?? 0);
        $ticket = $this->repository->findById($ticketId);

        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket not found';
            return;
        }

        $qr = (new QRCode())->render($ticket->qrToken);
        $vm = new TicketQrViewModel($qr);

        require __DIR__ . '/../Views/eventTicketQr/index.php';
    }
}