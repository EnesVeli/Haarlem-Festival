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

    // Shows the main history overview page
    public function index(): void
    {
        $viewModel = new HistoryIndexViewModel(
            $this->service->getHighlights(),
            $this->service->getTickets(),
            $this->service->getContent()
        );

        require __DIR__ . '/../Views/history/index.php';
    }

    // Shows a single highlight's detail page (e.g. /history/teylers-museum)
    public function detail(array $vars): void
    {
        $slug     = $vars['slug'] ?? '';
        $pageData = $this->service->getDetailPage($slug);

        // Redirect back if the slug doesn't match anything in the DB
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

    // Shows the booking page for a selected time slot
    public function booking(): void
    {
        // Generate a CSRF token for the booking form if one doesn't exist yet
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $selectedDate = $_GET['date'] ?? '';
        $selectedTime = $_GET['time'] ?? '';

        // Get tickets split into individual slots and family tickets
        $ticketGroups = $this->service->getGroupedTickets();

        // Match the time slot the user clicked; fall back to the first available
        $individualTicket = $this->findTicketByTimeSlot($ticketGroups['individual'], $selectedTime);
        $familyTicket     = $ticketGroups['family'][0] ?? null;

        $csrfToken = $_SESSION['csrf_token'];

        require __DIR__ . '/../Views/history/booking.php';
    }

    // Finds a ticket row matching the given time slot, or returns the first one as fallback
    private function findTicketByTimeSlot(array $tickets, string $timeSlot): ?array
    {
        foreach ($tickets as $ticket) {
            if ($ticket['time_slot'] === $timeSlot) {
                return $ticket;
            }
        }

        return $tickets[0] ?? null;
    }
}