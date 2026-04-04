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
            $this->service->getContent()
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

        // Date and time passed from the index page slot selector
        $selectedDate = $_GET['date'] ?? '';
        $selectedTime = $_GET['time'] ?? '';

        $eventId = (int)($_GET['event_id'] ?? 138);
        $typeId  = (int)($_GET['type_id']  ?? 138);

        // Get tickets grouped by type for the booking page
        $ticketGroups     = $this->service->getGroupedTickets();
        $individualTicket = null;

        // Match the selected time slot to the correct individual ticket row
        foreach ($ticketGroups['individual'] as $t) {
            if ($t['time_slot'] === $selectedTime) {
                $individualTicket = $t;
                break;
            }
        }
        // Fall back to first individual ticket if no match
        if (!$individualTicket) {
            $individualTicket = $ticketGroups['individual'][0] ?? null;
        }

        $familyTicket = $ticketGroups['family'][0] ?? null;
        $csrfToken    = $_SESSION['csrf_token'];

        require __DIR__ . '/../Views/history/booking.php';
    }
}