<?php

namespace App\Services;

use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\FormDataException;
use App\Models\Exceptions\OverBookingException;
use App\Models\Exceptions\UserNotLoggedInException;
use App\Models\RestaurantBooking;
use App\Repositories\RestaurantSortingOption;
use App\Repositories\YummyFoodTypeRepository;
use App\Repositories\YummyGuidesRepository;
use App\Repositories\YummyRestaurantsRepository;
use App\ViewModels\YummyBookViewModel;
use App\ViewModels\YummyHomeViewModel;
use App\ViewModels\YummyListViewModel;
use App\ViewModels\YummyRestaurantViewModel;
use DateInterval;
use DateTime;
use Exception;
use RoundingMode;

class YummyService
{
    private YummyGuidesRepository $guide_repository;
    private YummyRestaurantsRepository $restaurant_repository;
    private YummyFoodTypeRepository $type_repository;

    public function __construct()
    {
        $this->guide_repository = new YummyGuidesRepository();
        $this->restaurant_repository = new YummyRestaurantsRepository();
        $this->type_repository = new YummyFoodTypeRepository();
    }

    public function getHomeViewModel() : YummyHomeViewModel {
        $view_model = new YummyHomeViewModel();

        $view_model->restaurants = $this->restaurant_repository->getTopActiveRestaurants();
        $view_model->guides = $this->guide_repository->getTopActiveGuides();

        return $view_model;
    }

    public function getListViewModel(?string $place_type, ?string $meal_type, ?string $food_type, ?string $cuisine_type, ?int $sorting, ?int $page) : YummyListViewModel {
        $view_model = new YummyListViewModel();

        // Get Filtering and Sorting
        $view_model->current_place_types = isset($place_type) ? explode(',', $place_type) : [];
        $view_model->current_meal_types = isset($meal_type) ? explode(',', $meal_type) : [];
        $view_model->current_food_types = isset($food_type) ? explode(',', $food_type) : [];
        $view_model->current_cuisine_types = isset($cuisine_type) ? explode(',', $cuisine_type) : [];

        $view_model->sorting = $sorting ?? 0;

        $view_model->current_page = $page ?? 0;

        // Load restaurants from db
        $out = $this->restaurant_repository->getFilteredRestaurants($view_model->getAllCurrentTypes(), $view_model->current_page, RestaurantSortingOption::from($view_model->sorting));

        $view_model->restaurants = $out[1];
        $view_model->total_found_restaurants_number = $out[0];

        // Page calculations
        $page_count = round($out[0] / YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE, 0, RoundingMode::AwayFromZero);

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

        // Load types form db
        $all_types = $this->type_repository->getAllTypes() ?? [];  
        
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

        return $view_model;
    }

    public function getRestaurantViewModel(string $id) : YummyRestaurantViewModel {
        $view_model = new YummyRestaurantViewModel();

        $view_model->restaurant = $this->restaurant_repository->getRestaurantById((int)$id);
        $view_model->tags = $this->type_repository->getRestaurantTypes((int)$id);

        $view_model->images = $this->restaurant_repository->getRestaurantImages((int)$id) ?? [];

        $view_model->dishes = $this->restaurant_repository->getRestaurantDishes((int)$id) ?? [];

        return $view_model;
    }

    public function GetBookingViewModel(string $id) : YummyBookViewModel {
        $view_model = new YummyBookViewModel();
        
        $view_model->restaurant = $this->restaurant_repository->getRestaurantById($id);

        $view_model->time_slots = [];

        for($i = 0; $i < 14; $i++){
            array_push($view_model->time_slots, $this->restaurant_repository->getRestaurantTimeSlots($id, $i) ?? []);
        }

        $view_model->dates = [];
        $d = new DateTime();

        for ($i=0; $i < 14; $i++) { 
            array_push($view_model->dates, $d->format('d.m.Y l'));

            $d->add(new DateInterval('P1D')); // Adding one day to the date
        }

        return $view_model;
    }

    public function createBooking(?string $date_offset, ?int $adult_count, ?int $child_count, ?int $slot_id, ?string $comment){
        // Verify pathed data
        if($_SESSION['user_id'] == null) throw new UserNotLoggedInException();
        $user_id = $_SESSION['user_id'];

        if($date_offset == null || $date_offset > 12 || $date_offset < 0){
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

        $slot = $this->restaurant_repository->getRestaurantTimeSlotById($slot_id, $date_offset);

        if($slot == null) throw new FormDataException("Could not find time slot with the slot_id and date_offset.");

        if($slot->booked + $adult_count + $child_count > $slot->capacity) throw new OverBookingException();   
        
        // Creating new booking and reserving seats at reservation
        $booking = new RestaurantBooking();

        $booking->reservation_id = $slot->reservation_id;
        $booking->user_id = $user_id;
        $booking->adult_number = $adult_count;
        $booking->child_number = $child_count;
        $booking->comment = $comment;

        if(!$this->restaurant_repository->bookRestaurantTimeSlot($slot_id, $date_offset, $adult_count + $child_count)) throw new DBAccessException("Could not add bookings number to reservation slot.");

        // If creation failed revert increasing booking number.
        try{
            if(!$this->restaurant_repository->createBookingWithOffest($booking, $date_offset)) throw new DBAccessException("Could not create a new restaurant booking.");
        }
        catch(Exception $ex){
            $this->restaurant_repository->unbookRestaurantTimeSlot($slot_id, $date_offset, $adult_count + $child_count);

            throw $ex;
        }    
    }
}