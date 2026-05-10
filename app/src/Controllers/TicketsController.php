<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Models\Exceptions\QueryExecutionException;
use App\Services\HistoryService;
use App\Services\Jazz\JazzService;
use App\Services\StoriesService;
use App\Services\Yummy\YummyService;
use App\ViewModels\TicketsCategoryViewModel;
use App\ViewModels\TicketsHistoryCategoryViewModel;
use Exception;

/**
 * TicketsController — handles the public /tickets pages.
 *
 * Displays a main landing page with navigation tabs for every festival
 * event type (Jazz, Yummy, History, Stories). Each tab can point
 * to a dedicated sub-page that lists events with add-to-cart forms.
 */
class TicketsController extends BaseController
{
    public static $NUMBER_OF_TICKETS_PER_PAGE = 16;

    private YummyService $yummy_service;
    private JazzService $jazz_service;
    private StoriesService $stories_service;
    private HistoryService $history_service;

    public function __construct()
    {
        $this->yummy_service = YummyService::getInstance();
        $this->jazz_service = JazzService::getInstance();
        $this->stories_service = StoriesService::getInstance();
        $this->history_service = HistoryService::getInstance();
    }

    public function index(): void
    {
        $this->render('tickets/index', [
            'pageTitle' => 'Festival Program – The Festival Haarlem',
            'pageCSS'   => 'tickets.css',
        ]);
    }

    public function stories(): void
    {
        $events = null;
        $cur_page = null;
        $total_page_number = null;

        try{
            // get page number from url
            $cur_page = $this->getPage();

            // Get total number of story events for pagination calculations
            $event_number = $this->stories_service->getNumberOfStories();
            if($event_number === false) throw new QueryExecutionException("Failed to get story events number for ticekt page.");

            // Calc total number of pages
            $total_page_number = ceil($event_number / self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($cur_page > $total_page_number) $cur_page = $total_page_number;

            // Get paginated array of story events
            $events = $this->stories_service->getAllStoriesForTickets($cur_page, self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($events === false) throw new QueryExecutionException("Failed to get story events for ticekt page.");         
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. try again later." . $ex->getMessage());
        }

        $this->renderCategory('stories', $events, $cur_page, $total_page_number);
    }

    public function jazz(): void
    {
        $performers = null;
        $cur_page = null;
        $total_page_number = null;

        try{
            // get page number from url
            $cur_page = $this->getPage();

            // Get total number of active performers for pagination calculations
            $perf_number = $this->jazz_service->getNumberOfActivePerformers();
            if($perf_number === false) throw new QueryExecutionException("Failed to get performers number for ticekt page.");

            // Calc total number of pages
            $total_page_number = ceil($perf_number / self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($cur_page > $total_page_number) $cur_page = $total_page_number;

            // Get paginated array of active performers
            $performers = $this->jazz_service->getActivePerformersForTickets($cur_page, self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($performers === false) throw new QueryExecutionException("Failed to get performers for ticekt page.");           
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. try again later." . $ex->getMessage());
        }

        $this->renderCategory('jazz', $performers, $cur_page, $total_page_number);
    }

    public function history(): void
    {
        $time_slots = null;      
        $cur_page = null;
        $total_page_number = null;

        $first_day_offset = null;
        $last_day_offset = null;

        try{
            // get page number from url
            $cur_page = $this->getPage();

            // Get array of history time slots
            $time_slots = $this->history_service->getAllTimeSlots();
            if($time_slots === false) throw new QueryExecutionException("Failed to get history time slots for ticekt page.");

            // Calc total number of pages
            $offsets_per_page = ceil(self::$NUMBER_OF_TICKETS_PER_PAGE / count($time_slots));

            $total_page_number = ceil(HistoryService::$max_date_offset / $offsets_per_page);
            if($cur_page > $total_page_number) $cur_page = $total_page_number;

            // Calc offset
            $first_day_offset = $cur_page === 1 ? 0 : $offsets_per_page * ($cur_page - 1);
            $last_day_offset = $offsets_per_page * $cur_page;
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. try again later.");
        }

        $view_model = new TicketsHistoryCategoryViewModel($time_slots, $first_day_offset, $last_day_offset, $cur_page, $total_page_number);

        $error_message = Session::popTempError();

        $this->render('tickets/category-history', [
            'view_model' => $view_model,
            'error_message' => $error_message,
            'pageTitle' => $view_model->pageTitle,
            'pageCSS' => $view_model->pageCSS,
        ]);
    }

    public function yummy(): void
    {
        $restaurants = null;
        $cur_page = null;
        $total_page_number = null;

        try{
            // get page number from url
            $cur_page = $this->getPage();

            // Get total number of active restaurants for pagination calculations
            $res_number = $this->yummy_service->getNumberOfActiveRestaurants();
            if($res_number === false) throw new QueryExecutionException("Failed to get restaurant number for ticekt page.");

            // Calc total number of pages
            $total_page_number = ceil($res_number / self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($cur_page > $total_page_number) $cur_page = $total_page_number;

            // Get paginated array of active restaurants
            $restaurants = $this->yummy_service->getActiveRestaurantsForTickets($cur_page, self::$NUMBER_OF_TICKETS_PER_PAGE);
            if($restaurants === false) throw new QueryExecutionException("Failed to get restaurants for ticekt page.");          
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. try again later.");
        }

        $this->renderCategory('yummy', $restaurants, $cur_page, $total_page_number);
    }

    private function getPage() {
        if(isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT)){
            $page = $_GET['page'];

            if($page < 1) return 1;

            return $page;
        }

        return 1;
    }

    private function renderCategory(string $category_key, ?array $events, ?int $cur_page, ?int $total_page_number): void
    {
        $view_model = new TicketsCategoryViewModel($category_key, $events, $cur_page, $total_page_number);

        $error_message = Session::popTempError();

        $this->render('tickets/category', [
            'view_model' => $view_model,
            'error_message' => $error_message,
            'pageTitle' => $view_model->pageTitle,
            'pageCSS' => $view_model->pageCSS,
        ]);
    }
}
