<?php

namespace App\Controllers\Cms\Yummy;

use App\Controllers\Cms\BaseCmsController;
use App\Framework\Session;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\MaxCountExceededException;
use App\Services\Yummy\YummyCmsService;
use Exception;
use Uri\InvalidUriException;

class AdminYummyController extends BaseCmsController {
    private YummyCmsService $service;

    public function __construct(){
        $this->service = new YummyCmsService(); 
    }

    public function index(){
        $this->requireAdmin();

        $error_message = Session::pop("temp_error");

        try{
            $view_model = $this->service->getHomeViewModel();
        }
        catch(Exception $ex){
            if(!isset($error_message)) $error_message = '';

            $error_message = $error_message . "\nSomething went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/index.php';
    }

    public function editHome(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle'])) throw new EmptyFieldException();          

            if($_FILES['topper_image']['name'] != null){
                // Creating image file
                $file_name = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($_FILES['topper_image']['name'], PATHINFO_EXTENSION);
                $path = __DIR__ . '/../../../../public/assets/uploads/yummy/topper/' . $file_name;

                move_uploaded_file($_FILES['topper_image']['tmp_name'], $path);

                // Sending data to db
                $this->service->editHome($_POST['title'], $_POST['subtitle'], $file_name);
            }
            else{
                $this->service->editHome($_POST['title'], $_POST['subtitle'], null);
            }
        }
        catch(Exception $ex){    
            Session::set('temp_error', "Something went wrong try again later.");
        }

        header('location: /cms/yummy/');
    }

    public function list(){
        $this->requireAdmin();

        $error_message = Session::pop("temp_error");

        try{
            $view_model = $this->service->getListViewModel();
        }
        catch(Exception $ex){
            if(!isset($error_message)) $error_message = '';

            $error_message = $error_message . "\nSomething went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/list.php';
    }

    public function editList(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle'])) throw new EmptyFieldException();                    

            if($_FILES['topper_image']['name'] != null){
                // Creating image file
                $file_name = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($_FILES['topper_image']['name'], PATHINFO_EXTENSION);
                $path = __DIR__ . '/../../../../public/assets/uploads/yummy/topper/' . $file_name;

                move_uploaded_file($_FILES['topper_image']['tmp_name'], $path);

                // Sending data to db
                $this->service->editList($_POST['title'], $_POST['subtitle'], $file_name);
            }
            else{
                $this->service->editList($_POST['title'], $_POST['subtitle'], null);
            }    
        }
        catch(Exception $ex){    
            Session::set('temp_error', "Something went wrong try again later.");
        }

        header('location: /cms/yummy/list');
    }

    public function restaurantList(){
        $this->requireAdmin();

        try{
            $view_model = $this->service->getRestaurantListViewModel($_GET['sort'] ?? 0, $_GET['order'] ?? 0, $_GET['page'] ?? 0);
        }
        catch(InvalidUriException $ex){
            $error_message = "Invalid uri arguments.";
        }
        catch(Exception $ex){
            $error_message = "Something went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/restaurant-list.php';
    }

    public function restaurant(){
        $this->requireAdmin();

        $error_message = Session::pop("temp_error");
        $success_message = Session::pop("temp_success");

        try{
            if(!isset($_GET['id'])) throw new InvalidUriException();

            $view_model = $this->service->getRestaurantViewModel($_GET['id']);
        }
        catch(Exception $ex){
            if(!isset($error_message)) $error_message = '';

            $error_message = $error_message . "\nSomething went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/restaurant.php';
    }

    public function editRestaurant(){
        
    }

    public function addImage(){
        $this->requireAdmin();

        print_r($_POST);

        echo 'Files:';

        print_r($_FILES);
        
        exit;

        try{
            if(!isset($_POST['restaurant_id']) || !isset($_FILES['image_add']['tmp_name'])) throw new EmptyFieldException(); 
                     
            // Creating image file
            $file_name = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($_FILES['image_add']['name'], PATHINFO_EXTENSION);
            $path = __DIR__ . '/../../../../public/assets/uploads/yummy/restaurants/' . $file_name;
            move_uploaded_file($_FILES['image_add']['tmp_name'], $path);    

            // Creating image
            if($this->service->addRestaurantImage($_POST['restaurant_id'], $file_name)){
                Session::set('success_message', "Successfully added new image to restaurant.");
            } 
        }
        catch(MaxCountExceededException $ex){
            Session::set('temp_error', "The maximum count of additional images for restaurant is 11. Delete some images to add a new one's.");
        }
        catch(EmptyFieldException $ex){
            Session::set('temp_error', "You must upload an image in order to add additional images to restaurant.");
        }
        catch(Exception $ex){
            Session::set('temp_error', "Something went wrong try again later.");
        }

        header('location: /cms/yummy/restaurant');
    }
}