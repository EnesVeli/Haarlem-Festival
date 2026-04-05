<?php

namespace App\Controllers;

use App\Repositories\FestivalTicketRepository;
use chillerlan\QRCode\QRCode;

class EventTicketQrController
{
    private FestivalTicketRepository $repository;

    public function __construct()
    {
        $this->repository = new FestivalTicketRepository();
    }

    public function show(array $vars): void
    {
        $ticketId = (int)($vars['id'] ?? 0);
        $ticket = $this->repository->findById($ticketId);
    
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket not found';
            return;
        }
    
        $qr = (new QRCode())->render($ticket['qr_token']);
    
        require __DIR__ . '/../Views/eventTicketQr/index.php';
    }
}