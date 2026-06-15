<?php

namespace App\Controllers;

use App\Framework\Session;
use App\Interfaces\Services\ITicketScanService;
use App\Services\TicketScanService;
use App\ViewModels\TicketScanner\TicketQrViewModel;

class TicketQrController extends BaseController
{
    private ITicketScanService $service;

    public function __construct()
    {
        $this->service = TicketScanService::getInstance();
    }

    public function show(array $vars): void
    {
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $ticketId = (int) ($vars['id'] ?? 0);

        if (!Session::isAdmin() && !Session::isEmployee()) {
            $ownerId = $this->service->getTicketOwnerId($ticketId);
            $currentUserId = Session::currentUser()->user_id;
            if ($ownerId === null || $ownerId !== $currentUserId) {
                http_response_code(404);
                echo 'Ticket not found';
                return;
            }
        }

        $qr = $this->service->getTicketQr($ticketId);

        if ($qr === null) {
            http_response_code(404);
            echo 'Ticket not found';
            return;
        }

        $vm = new TicketQrViewModel($qr);

        $this->render('EventTicketQr/index', [
            'vm'        => $vm,
            'pageTitle' => 'My Ticket QR',
            'mainClass' => 'qr-main',
            'user'      => Session::user(),
        ]);
    }
}