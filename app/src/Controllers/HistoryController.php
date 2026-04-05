<?php

namespace App\Controllers;

use App\Services\HistoryService;
use App\ViewModels\HistoryIndexViewModel;
use App\ViewModels\HistoryDetailViewModel;

class HistoryController
{
    private HistoryService $service;

    public function __construct()
    {
        $this->service = new HistoryService();
    }

    public function index()
    {
        $viewModel = new HistoryIndexViewModel(
            $this->service->getHighlights(),
            $this->service->getTickets(),
            $this->service->getContent()
        );

        require __DIR__ . '/../Views/history/index.php';
    }

    public function detail($vars)
    {
        $slug     = $vars['slug'] ?? '';
        $pageData = $this->service->getDetailPage($slug);

        if (!$pageData) {
            header('Location: /history');
            exit;
        }

        $viewModel = new HistoryDetailViewModel(
            $pageData['detail'],
            $pageData['sections'],
            $pageData['gallery'],
            $pageData['facts'],
            $this->service->getOtherHighlights($slug)
        );

        require __DIR__ . '/../Views/history/detail.php';
    }

    public function booking(): void
    {
        $ticketId     = (int)($_GET['ticket_id'] ?? 0);
        $tickets      = $this->service->getTickets();
        $ticket       = array_values(array_filter($tickets, fn($t) => $t['id'] === $ticketId))[0] ?? ($tickets[0] ?? []);
        $selectedDate = $_GET['date'] ?? 'Thursday';
        $selectedTime = $_GET['time'] ?? '';

        // Load individual and family prices from the dedicated prices table
        $prices           = $this->service->getTicketPrices();
        $individualTicket = $prices['individual'] ?? ['price' => 17.50];
        $familyTicket     = $prices['family']     ?? ['price' => 60.00];

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrfToken = $_SESSION['csrf_token'];

        $eventId = 138;
        $typeId  = 138;

        require __DIR__ . '/../Views/history/booking.php';
    }
}