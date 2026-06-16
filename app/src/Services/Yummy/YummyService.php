<?php

namespace App\Services\Yummy;

use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\DBDataFetchException;
use App\Models\Exceptions\FormDataException;
use App\Models\Exceptions\OverBookingException;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\Exceptions\UserNotLoggedInException;
use App\Models\OpeningHours;
use App\Models\Restaurant;
use App\Models\YummyBooking;
use App\Repositories\OrderRepository;
use App\Repositories\RestaurantSortingOption;
use App\Repositories\YummyCmsRepository;
use App\Repositories\YummyFoodTypeRepository;
use App\Repositories\YummyGuidesRepository;
use App\Repositories\YummyRestaurantsRepository;
use App\Services\OrderService;
use App\ViewModels\Yummy\YummyBookViewModel;
use App\ViewModels\Yummy\YummyHomeViewModel;
use App\ViewModels\Yummy\YummyListViewModel;
use App\ViewModels\Yummy\YummyRestaurantViewModel;
use DateInterval;
use DateTime;
use InvalidArgumentException;
use RoundingMode;

class YummyService
{
    public static int $max_date_offset = 12;

    private static ?YummyService $_instance = null;

    public static function getInstance() : YummyService {
        if(self::$_instance === null) self::$_instance = new YummyService(YummyGuidesRepository::getInstance(), YummyRestaurantsRepository::getInstance(), OrderRepository::getInstance(),
            YummyFoodTypeRepository::getInstance(), YummyCmsRepository::getInstance(), OrderService::getInstance());

        return self::$_instance;
    }

    private YummyGuidesRepository $guide_repository;
    private YummyRestaurantsRepository $restaurant_repository;
    private OrderRepository $order_repository;
    private YummyFoodTypeRepository $type_repository;
    private YummyCmsRepository $cms_repository;
    private OrderService $order_service;


    private function __construct(YummyGuidesRepository $guide_repository, YummyRestaurantsRepository $restaurant_repository, OrderRepository $order_repository,
        YummyFoodTypeRepository $type_repository, YummyCmsRepository $cms_repository, OrderService $order_service)
    {
        $this->guide_repository = $guide_repository;
        $this->restaurant_repository = $restaurant_repository;
        $this->order_repository = $order_repository;
        $this->type_repository = $type_repository;
        $this->cms_repository = $cms_repository;
        $this->order_service = $order_service;
    }
    
    public function getActiveRestaurantsForTickets(int $page, int $res_per_page) : array {
        $res = $this->restaurant_repository->getActiveRestaurantsForTickets($page, $res_per_page);

        if($res === false) throw new DBDataFetchException("Failed to get active restaurants for tickets.");

        if($res === null) return [];

        return $res;
    }

    public function getNumberOfActiveRestaurants() : int|bool {
        $res = $this->restaurant_repository->getNumberOfActiveRestaurants();

        if($res === false) throw new DBDataFetchException("Failed to number of active restaurants.");

        return $res;
    }

    public function getTopActiveRestaurants() : array {
        $res = $this->restaurant_repository->getTopActiveRestaurants();

        if($res === false) throw new DBDataFetchException("Failed to get top active restaurants.");

        if($res === null) return [];

        return $res;
    }

    public function getTopActiveGuides() : array {
        $res = $this->guide_repository->getTopActiveGuides();

        if($res === false) throw new DBDataFetchException("Failed to get top active guides.");

        if($res === null) return [];

        return $res;
    }

    public function getHomeData() : array {
        $res = $this->cms_repository->getHomeData();
        
        if($res == null) throw new DBDataFetchException("Failed to get home page data.");

        return $res;
    }

    public function getListData() : array {
        $res = $this->cms_repository->getListData();

        if($res == null) throw new DBDataFetchException("Failed to get list page data.");

        return $res;
    }

    public function getFilteredRestaurants(array $types, int $current_page, RestaurantSortingOption $sorting) : array {
        $res = $this->restaurant_repository->getFilteredRestaurants($types, $current_page, $sorting);

        if($res === false) throw new DBDataFetchException("Failed to get restaurants list.");

        if($res === null) return [];

        return $res;
    }

    public function countFilteredRestaurants(array $types) {
        $res = $this->restaurant_repository->countFilteredRestaurants($types);

        if($res === false) throw new DBDataFetchException("Failed to get total number of restaurants fiting the filter.");

        return $res;
    }

    public function fillListViewPagiation(YummyListViewModel $view_model) {
        // Page calculations
        $page_count = round(count($view_model->restaurants) / YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE, 0, RoundingMode::AwayFromZero);
        $page = $view_model->current_page;

        $offset = 0; // Left offset of pages button
        $limit = 0; // Right offset of pages button

        if($page < abs($page - $page_count + 1)){ // If current page is closer to first page than last, start from offset
            for (; $offset < 3; $offset++) { 
                if($page - $offset <= 0) break;
            }

            for (; $limit < 7 - $offset; $limit++) { 
                if($page + $limit >= $page_count) break;
            }

        }  
        else{ // Otherwise from limit
            for (; $limit < 4; $limit++) { 
                if($page + $limit >= $page_count) break;
            }

            for (; $offset < 7 - $limit; $offset++) { 
                if($page - $offset <= 0) break;
            }                       
        } 

        $view_model->total_pages_number = $page_count;
        $view_model->page_offset = $offset;
        $view_model->page_limit = $limit;
    }

    public function fillListViewTypes(YummyListViewModel $view_model){
        // Load types form db
        $all_types = $this->type_repository->getAllTypes();  

        if($all_types === false) throw new DBDataFetchException("Failed to get all restaurant types.");
        
        for($i = 0; $i < count($all_types); $i++){ 
            switch($all_types[$i]->category){
                case 0:
                    $view_model->all_place_types[] = $all_types[$i];
                    break;
                case 1:
                    $view_model->all_meal_types[] = $all_types[$i];
                    break;
                case 2:
                    $view_model->all_food_types[] = $all_types[$i];
                    break;
                case 3:
                    $view_model->all_cuisine_types[] = $all_types[$i];
                    break;
            }
        }
    }

    public function getRestaurantById(int $id) : Restaurant {
        $res = $this->restaurant_repository->getRestaurantById($id);

        if($res === false) throw new DBDataFetchException("Failed to get restaurant by id.");

        if($res === null || !$res->active) throw new InvalidArgumentException("No restaurant with given id.");

        return $res;
    }

    public function getRestaurantOpeningHours(int $restaurant_id) : OpeningHours {
        $res = $this->restaurant_repository->getRestaurantOpeningHours($restaurant_id);

        if($res === false) throw new DBDataFetchException("Failed to get restaurant opening hours.");

        if($res === null) throw new InvalidArgumentException("No opening hours with given restaurant id.");

        return $res;
    }

    public function getRestaurantTypes(int $restaurant_id) : array {
        $res = $this->type_repository->getRestaurantTypes($restaurant_id);

        if($res === false) throw new DBDataFetchException("Failed to get restaurant food types.");

        if($res === null) return [];

        return $res;
    }

    public function getRestaurantImages(int $restaurant_id) : array {
        $res = $this->restaurant_repository->getRestaurantImages($restaurant_id);

        if($res === false) throw new DBDataFetchException("Failed to get restaurant images.");

        if($res === null) return [];

        return $res;
    }

    public function getRestaurantDishes(int $restaurant_id) : array {
        $res = $this->restaurant_repository->getRestaurantDishes($restaurant_id);

        if($res === false) throw new DBDataFetchException("Failed to get restaurant dishes.");

        if($res === null) return [];

        return $res;
    }

    public function loadRestaurantTimeSlots(int $id, array & $time_slots) {
        for($i = 0; $i < self::$max_date_offset; $i++){
            array_push($time_slots, $this->restaurant_repository->loadRestaurantTimeSlots($id, $i) ?? []);
        }
    }

    public function fillInRestaurantTimeSlotDates(array & $dates) {
        $d = new DateTime();

        for ($i=0; $i < 14; $i++) { 
            array_push($dates, $d->format('d.m.Y l'));

            $d->add(new DateInterval('P1D')); // Adding one day to the date
        }
    }

    public function createBooking(?string $date_offset, ?int $adult_count, ?int $child_count, ?int $slot_id, ?string $comment){
        // Verify pathed data
        if($_SESSION['user_id'] == null) throw new UserNotLoggedInException();
        $user_id = $_SESSION['user_id'];

        if($date_offset == null || $date_offset > 13 || $date_offset < 0){
            throw new FormDataException("Invalid date offset.");
        }

        if($adult_count == null || $adult_count > 24 || $adult_count < 1){
            throw new FormDataException("Invalid adult count.");
        }

        if($child_count == null || $child_count > 24 || $child_count < 0){
            throw new FormDataException("Invalid child count.");
        }

        if($slot_id == null){
            throw new FormDataException("Invalid slot id.");
        }

        $slot = $this->restaurant_repository->getRestaurantTimeSlotByDateOffset($slot_id, $date_offset);

        if($slot == null) throw new FormDataException("Could not find time slot with the slot_id and date_offset.");

        if($slot->booked + $adult_count + $child_count > $slot->capacity) throw new OverBookingException();   


        // Creating new booking
        $booking = new YummyBooking();

        $booking->reservation_id = $slot->reservation_id;
        $booking->user_id = $user_id;
        $booking->date = new DateTime($slot->date->format('Y-m-d') . ' ' . $slot->time->format('H:i:s'));
        $booking->adult_number = $adult_count;
        $booking->child_number = $child_count;
        $booking->comment = $comment;

        //if(!$this->restaurant_repository->bookRestaurantTimeSlot($slot_id, $date_offset, $adult_count + $child_count)) throw new DBAccessException("Could not add bookings number to reservation slot.");

        $booking_id = $this->order_repository->createYummyBooking($booking);
        if($booking_id == null) throw new QueryExecutionException("Failed to create new restaurant booking.");   

        $booking->booking_id = $booking_id;

        $this->order_service->addBookingToCart($user_id, $booking);
    }
}