<?php
namespace App\Controllers;

use App\Interfaces\INonStoriesTicketsService;
use App\Services\StoriesService;
use App\ViewModels\TicketsCategoryViewModel;
use App\ViewModels\TicketsStoriesViewModel;

/**
 * TicketsController — handles the public /tickets pages.
 *
 * Displays a main landing page with navigation tabs for every festival
 * event type (Jazz, Dance, Yummy, History, Stories). Each tab can point
 * to a dedicated sub-page that lists events with add-to-cart forms.
 */
class TicketsController extends BaseController
{
    /** @var StoriesService Service for fetching story events. */
    private StoriesService $storiesService;
    private INonStoriesTicketsService $nonStoriesTicketsService;

    /**
     * Constructor — receives the StoriesService via dependency injection.
     *
     * @param StoriesService $storiesService Service for story events
     */
    public function __construct(StoriesService $storiesService, INonStoriesTicketsService $nonStoriesTicketsService)
    {
        $this->storiesService = $storiesService;
        $this->nonStoriesTicketsService = $nonStoriesTicketsService;
    }

    /**
     * GET /tickets — main landing page with event-type tabs.
     *
     * Renders the tab navigation shell. The default active tab redirects
     * or shows a brief overview; visitors click a tab to see that event
     * type's ticket listing.
     *
     * @param array $vars Route parameters (unused)
     *
     * @return void
     */
    public function index(array $vars = []): void
    {
        $this->render('tickets/index', [
            'pageTitle' => 'Festival Program – The Festival Haarlem',
            'pageCSS'   => 'tickets.css',
        ]);
    }

    /**
     * GET /tickets/stories — Stories ticket listing grouped by day.
     *
     * Fetches every Story event from the service, wraps them in a
     * TicketsStoriesViewModel (which groups them by day), and renders
     * the tickets/stories view with add-to-cart POST forms.
     *
     * @param array $vars Route parameters (unused)
     *
     * @return void
     */
    public function stories(array $vars = []): void
    {
        $events = $this->storiesService->getAllEvents();
        $viewModel = new TicketsStoriesViewModel($events, $this->ensureCsrfToken());

        $this->render('tickets/stories', [
            'viewModel' => $viewModel,
            'pageTitle'  => $viewModel->pageTitle,
            'pageCSS'    => $viewModel->pageCSS,
        ]);
    }

    /**
     * Renders Jazz ticket category page.
     *
     * @param array $vars Route parameters (unused)
     */
    public function jazz(array $vars = []): void
    {
        $this->renderCategory('jazz');
    }

    /**
     * Renders Dance ticket category page.
     *
     * @param array $vars Route parameters (unused)
     */
    public function dance(array $vars = []): void
    {
        $this->renderCategory('dance');
    }

    /**
     * Renders History ticket category page.
     *
     * @param array $vars Route parameters (unused)
     */
    public function history(array $vars = []): void
    {
        $this->renderCategory('history');
    }

    /**
     * Renders Yummy ticket category page.
     *
     * @param array $vars Route parameters (unused)
     */
    public function yummy(array $vars = []): void
    {
        $this->renderCategory('yummy');
    }

    /**
     * Shared category rendering logic for non-stories tickets.
     */
    private function renderCategory(string $categoryKey): void
    {
        $eventsByDay = $this->nonStoriesTicketsService->getCategoryTickets($categoryKey);
        $viewModel = new TicketsCategoryViewModel($categoryKey, $eventsByDay, $this->ensureCsrfToken());

        $success = $this->popFlash('cart_success');
        $error = $this->popFlash('cart_error');

        $this->render('tickets/category', [
            'viewModel' => $viewModel,
            'success' => $success,
            'error' => $error,
            'pageTitle' => $viewModel->pageTitle,
            'pageCSS' => $viewModel->pageCSS,
        ]);
    }
}
