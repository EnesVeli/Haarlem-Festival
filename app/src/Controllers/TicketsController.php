<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Models\Exceptions\QueryExecutionException;
use App\Services\StoriesService;
use App\Services\Yummy\YummyService;
use App\ViewModels\TicketsCategoryViewModel;
use App\ViewModels\TicketsStoriesViewModel;
use Exception;

/**
 * TicketsController — handles the public /tickets pages.
 *
 * Displays a main landing page with navigation tabs for every festival
 * event type (Jazz, Dance, Yummy, History, Stories). Each tab can point
 * to a dedicated sub-page that lists events with add-to-cart forms.
 */
class TicketsController extends BaseController
{
    public static $NUMBER_OF_TICKETS_PER_PAGE = 6;

    private StoriesService $storiesService;
    private YummyService $yummy_service;

    public function __construct()
    {
        $this->storiesService = StoriesService::getInstance();
        $this->yummy_service = YummyService::getInstance();
    }

    /**
     * GET /tickets — main landing page with event-type tabs.
     *
     * Renders the tab navigation shell. The default active tab redirects
     * or shows a brief overview; visitors click a tab to see that event
     * type's ticket listing.
     *
     * @param array $vars Route parameters
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
     * @return void
     */
    public function stories(): void
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
     */
    public function jazz(): void
    {
        try{
            

            $this->renderCategory('jazz');
        }
        catch(Exception $ex){

        }

        header("location: /tickets");
    }

    /**
     * Renders Dance ticket category page.
     */
    public function dance(): void
    {
        $this->renderCategory('dance');
    }

    /**
     * Renders History ticket category page.
     */
    public function history(): void
    {
        $this->renderCategory('history');
    }

    /**
     * Renders Yummy ticket category page.
     */
    public function yummy(): void
    {
        $restaurants = null;

        try{
            $restaurants = $this->yummy_service->getActiveRestaurantsForTickets($this->getPage(), self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($restaurants === false) throw new QueryExecutionException("Failed to get restaurants for ticekt page.");
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. try again later.");
        }

        $this->renderCategory('yummy', $restaurants);
    }

    private function getPage() {
        if(isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT)){
            $page = $_GET['page'];

            if($page < 1) return 1;

            return $page;
        }

        return 1;
    }

    private function renderCategory(string $category_key, array $events): void
    {
        $view_model = new TicketsCategoryViewModel($category_key, $events);

        $error_message = Session::popTempError();

        $this->render('tickets/category', [
            'view_model' => $view_model,
            'error_message' => $error_message,
            'pageTitle' => $view_model->pageTitle,
            'pageCSS' => $view_model->pageCSS,
        ]);
    }
}
