<?php

namespace App\Controllers;

use App\Services\FestivalTicketScanService;

class EventTicketScanController
{
    private FestivalTicketScanService $service;

    public function __construct()
    {
        $this->service = new FestivalTicketScanService();
    }

    public function index(): void
    {
        require __DIR__ . '/../Views/eventTicketScan/index.php';
    }

    public function scan(): void
    {
        $result = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qrToken = trim($_POST['qr_token'] ?? '');
            $result = $this->service->scanTicket($qrToken);
        }

        require __DIR__ . '/../Views/eventTicketScan/index.php';
    }
}