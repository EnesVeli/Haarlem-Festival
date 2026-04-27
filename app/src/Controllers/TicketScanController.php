<?php

namespace App\Controllers;

use App\Exceptions\TicketScanException;
use App\Framework\Session;
use App\Services\TicketScanService;
use App\ViewModels\TicketScanner\TicketScanViewModel;

class TicketScanController
{
    private TicketScanService $service;

    public function __construct()
    {
        $this->service = new TicketScanService();
    }

    private function requireEmployee(): void
    {
        $user = Session::user();

        if (!$user || !isset($user['role']) || $user['role'] !== 'employee') {
            header('Location: /login');
            exit;
        }
    }

    public function index(): void
    {
        $this->requireEmployee();

        $vm = new TicketScanViewModel();
        require __DIR__ . '/../Views/eventTicketScan/index.php';
    }

    public function scan(): void
    {
        $this->requireEmployee();

        $result = null;

        $scanValue = trim($_POST['scan_value'] ?? '');

        try {
            $ticket = $this->service->scanTicket($scanValue);

            $result = [
                'status' => 'success',
                'message' => 'Ticket is valid. Entry allowed.',
                'ticket' => $ticket
            ];
        } catch (TicketScanException $error) {
            $result = [
                'status' => 'warning',
                'message' => $error->getMessage()
            ];
        } catch (\Throwable $error) {
            $result = [
                'status' => 'error',
                'message' => 'Something went wrong while scanning the ticket.' . $error->getMessage()
            ];
        }

        $vm = new TicketScanViewModel($result);
        require __DIR__ . '/../Views/eventTicketScan/index.php';
    }
}