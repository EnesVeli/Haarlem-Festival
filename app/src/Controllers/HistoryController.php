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

    /**
     * Display the main history page
     */
    public function index()
    {
        $viewModel = new HistoryIndexViewModel(
            $this->service->getHighlights(),
            $this->service->getTickets(),
            $this->service->getContent()   // returns raw rows — ViewModel organizes them
        );

        require __DIR__ . '/../Views/history/index.php';
    }

    /**
     * Display a detail page for a specific highlight
     */
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
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $eventId   = (int)($_GET['event_id'] ?? 138); // Event table ID
    $typeId    = (int)($_GET['type_id']  ?? 138); // Ticket_Type table ID
    $tickets   = $this->service->getTickets();
    $ticket    = array_values(array_filter($tickets, fn($t) => $t['id'] === (int)($_GET['ticket_id'] ?? 1)))[0] ?? ($tickets[0] ?? []);
    $csrfToken = $_SESSION['csrf_token'];

    require __DIR__ . '/../Views/history/booking.php';
}
}