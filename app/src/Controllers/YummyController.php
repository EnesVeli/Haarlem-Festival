<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\FormDataException;
use App\Models\Exceptions\OverBookingException;
use App\Models\Exceptions\UserNotLoggedInException;
use App\Repositories\RestaurantSortingOption;
use App\Services\Yummy\YummyService;
use App\ViewModels\Yummy\YummyBookViewModel;
use App\ViewModels\Yummy\YummyHomeViewModel;
use App\ViewModels\Yummy\YummyListViewModel;
use App\ViewModels\Yummy\YummyRestaurantViewModel;
use Exception;

class YummyController
{
    private YummyService $service;

    public function __construct()
    {
        $this->service = YummyService::getInstance();
    }

    public function index()
    {
        $error_message = Session::popTempError();

        try{
            $view_model = new YummyHomeViewModel();

            $view_model->restaurants = $this->service->getTopActiveRestaurants();
            $view_model->guides = $this->service->getTopActiveGuides();

            $home_data = $this->service->getHomeData();

            $view_model->title = $home_data['home_title'];
            $view_model->subtitle = $home_data['home_subtitle'];
            $view_model->topper_path = $home_data['home_image'];
        } 
        catch(Exception $ex){
            $error_message = 'Something went wrong, try again later';
        }   

        require __DIR__ . '/../Views/yummy/home.php';
    }

    public function guide(){
        header("Location: /yummy");
    }

    public function list(){
        $error_message = Session::popTempError();

        try{
            $view_model = new YummyListViewModel();

            // Get data from yummy cms
            $list_data = $this->service->getListData();          

            $view_model->title = $list_data['list_title'];
            $view_model->subtitle = $list_data['list_subtitle'];
            $view_model->topper_path = $list_data['list_image'];

            // Get Filtering and Sorting
            $view_model->current_place_types = isset($_GET['place_type']) ? explode(',', $_GET['place_type']) : [];
            $view_model->current_meal_types = isset($_GET['meal_type']) ? explode(',', $_GET['meal_type']) : [];
            $view_model->current_food_types = isset($_GET['food_type']) ? explode(',', $_GET['food_type']) : [];
            $view_model->current_cuisine_types = isset($_GET['cuisine_type']) ? explode(',', $_GET['cuisine_type']) : [];

            $view_model->sorting = $_GET['sorting'] ?? 0;

            $view_model->current_page = $_GET['page'] ?? 0;

            // Load restaurants from db
            $all_types = $view_model->getAllCurrentTypes();

            $view_model->restaurants = $this->service->getFilteredRestaurants($all_types, $view_model->current_page, RestaurantSortingOption::from($view_model->sorting));     

            $view_model->total_found_restaurants_number = $this->service->countFilteredRestaurants($all_types);
            
            // Fill in other data
            $this->service->fillListViewPagiation($view_model);

            $this->service->fillListViewTypes($view_model);
        } 
        catch(Exception $ex){
            Session::setTempError('Something went wrong, try again later');
        }          
        
        require __DIR__ . '/../Views/yummy/list.php';
    }

    public function restaurant(){
        try{
            if(!isset($_GET['id']) || !is_numeric($_GET['id'])) throw new EmptyPostException();
            $restaurant_id = $_GET['id'];

            $view_model = new YummyRestaurantViewModel();

            $view_model->restaurant = $this->service->getRestaurantById($restaurant_id);
            
            $view_model->hours = $this->service->getRestaurantOpeningHours($restaurant_id);
            $view_model->tags = $this->service->getRestaurantTypes($restaurant_id);

            $view_model->images = $this->service->getRestaurantImages($restaurant_id);

            $view_model->dishes = $this->service->getRestaurantDishes($restaurant_id);

            require __DIR__ . '/../Views/yummy/restaurant.php';
            exit;
        } 
        catch(Exception $ex){
            Session::setTempError('Something went wrong, try again later');
        }          

        header('location: /yummy/list');
    }

    public function bookingPage(){
        if(!$this->isLoggedIn()){
            Session::setTempError("In order to book a table you need to login first.");
            header("Location: /login");
            exit;
        }

        $error_message = Session::popTempError();

        try{
            if(!isset($_GET['id']) || !is_numeric($_GET['id'])) throw new EmptyPostException();
            $restaurant_id = $_GET['id'];

            $view_model = new YummyBookViewModel();
        
            $view_model->restaurant = $this->service->getRestaurantById($restaurant_id);

            $view_model->time_slots = [];
            $view_model->dates = [];

            $this->service->loadRestaurantTimeSlots($restaurant_id, $view_model->time_slots);
            $this->service->fillInRestaurantTimeSlotDates($view_model->dates);
        }
        catch(Exception $ex){
            Session::setTempError('Something went wrong, try again later');
        }  

        require __DIR__ . '/../Views/yummy/book.php';
    }
    public function book(){
        if(!$this->isLoggedIn()){
            //$error = "In order to book a table you need to login first.";
            header("Location: /login");
            exit;
        }

        try{
            $this->service->createBooking($_POST['date_offset'], $_POST['adult_count'], $_POST['child_count'], $_POST['slot_id'], $_POST['comment']);
        }
        catch(UserNotLoggedInException $ex){
            header("Location: /login");
            exit;
        }
        catch(FormDataException $ex){
            $this->redirectToBook("Something went wrong try again later." . $ex->getMessage());
            exit;
        } 
        catch(OverBookingException $ex){
            $this->redirectToBook("You are trying to book more seats than are avaliable." . $ex->getMessage());
            exit;
        } 
        catch(DBAccessException $ex){
            $this->redirectToBook("Something went wrong try again later." . $ex->getMessage());
            exit;
        } 
        catch(Exception $ex){
            $this->redirectToBook("Something went wrong try again later." . $ex->getMessage());
            exit;
        } 

        require __DIR__ . '/../Views/yummy/booking-success.php';
    }

    private function redirectToBook(string $error){
        if($_POST['restaurant_id'] == null){
            header("location: /yummy/book?err=" . urlencode($error));
        }
        else{
            header("location: /yummy/book?id=" . $_POST['restaurant_id'] . '&err=' . urlencode($error));
        }  
    }

    private function isLoggedIn() : bool
    {
        return isset($_SESSION['user_id']);
    }
}