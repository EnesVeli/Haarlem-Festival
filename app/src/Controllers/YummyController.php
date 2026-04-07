<?php
namespace App\Controllers;

use App\Config;
use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\FormDataException;
use App\Models\Exceptions\OverBookingException;
use App\Models\Exceptions\UserNotLoggedInException;
use App\Services\Yummy\YummyService;
use Exception;

class YummyController
{
    private YummyService $service;

    public function __construct()
    {
        $this->service = new YummyService();
    }

    public function index()
    {
        $pageTitle = 'Yummy - Haarlem Festival';

        try{
            $view_model = $this->service->getHomeViewModel();
        } 
        catch(Exception $ex){
            $error_message = 'Something went wrong, try again later';
        }   

        require __DIR__ . '/../Views/yummy/home.php';
    }

    public function list(){
        $pageTitle = 'Yummy - Restaurant List';

        try{
            $view_model = $this->service->getListViewModel($_GET['place_type'] ?? null, $_GET['meal_type'] ?? null, $_GET['food_type'] ?? null, $_GET['cuisine_type'] ?? null, $_GET['sorting'] ?? null, $_GET['page'] ?? null);
        } 
        catch(Exception $ex){
            $error_message = 'Something went wrong, try again later';
        }          

        require __DIR__ . '/../Views/yummy/list.php';
    }

    public function restaurant(){
        try{
            $id = $_GET['id'] ?? null;

            $view_model = $this->service->getRestaurantViewModel($id);

            $pageTitle = 'Yummy - Restaurant List';
        } 
        catch(Exception $ex){
            $error_message = 'Something went wrong, try again later';
        }          

        require __DIR__ . '/../Views/yummy/restaurant.php';
    }

    public function bookingPage(){
        if(!$this->isLoggedIn()){
            //$error = "In order to book a table you need to login first.";
            header("Location: /login");
            exit;
        }

        try{
            $id = $_GET['id'] ?? null;

            $view_model = $this->service->GetBookingViewModel($id);

            $pageTitle = 'Yummy - Restaurant List';

            $error_message = $_GET['err'] ?? null;
        }
        catch(Exception $ex){
            $error_message = 'Something went wrong, try again later';
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