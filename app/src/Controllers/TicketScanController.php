<?php

namespace App\Controllers;

use App\Exceptions\TicketScanException;
use App\Framework\Session;
use App\Interfaces\Services\ITicketScanService;
use App\Models\TicketScanResult;
use App\Services\TicketScanService;
use App\ViewModels\TicketScanner\TicketScanViewModel;

class TicketScanController
{
    private ITicketScanService $service;

    public function __construct()
    {
        $this->service = TicketScanService::getInstance();
    }

    private function requireEmployee(): void
    {
        if (!Session::isEmployee()) {
            header('Location: /login');
            exit;
        }
    }

    public function index(): void
    {
        $this->requireEmployee();

        $vm = new TicketScanViewModel();
        require __DIR__ . '/../Views/EventTicketScan/index.php';
    }

    public function scan(): void
    {
        $this->requireEmployee();

        $result = null;

        $scanValue = trim($_POST['scan_value'] ?? '');

        try {
            $ticket = $this->service->scanTicket($scanValue);
            $result = new TicketScanResult('success', 'Ticket is valid. Entry allowed.', $ticket);
        } catch (TicketScanException $error) {
            $result = new TicketScanResult('warning', $error->getMessage());
        } catch (\Throwable $error) {
            $result = new TicketScanResult('error', 'Something went wrong while scanning the ticket.');
        }

        $vm = new TicketScanViewModel($result);
        require __DIR__ . '/../Views/EventTicketScan/index.php';
    }
}