<?php
namespace App\Controllers;

use App\Services\StoriesService;
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

    /**
     * Constructor — receives the StoriesService via dependency injection.
     *
     * @param StoriesService $storiesService Service for story events
     */
    public function __construct(StoriesService $storiesService)
    {
        $this->storiesService = $storiesService;
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

        // Generate or reuse a CSRF token for the add-to-cart forms
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $viewModel = new TicketsStoriesViewModel($events, $_SESSION['csrf_token']);

        $this->render('tickets/stories', [
            'viewModel' => $viewModel,
            'pageTitle'  => $viewModel->pageTitle,
            'pageCSS'    => $viewModel->pageCSS,
        ]);
    }
}
