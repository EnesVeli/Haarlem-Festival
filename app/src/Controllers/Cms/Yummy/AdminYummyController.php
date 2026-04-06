<?php

namespace App\Controllers\Cms\Yummy;

use App\Controllers\Cms\BaseCmsController;
use App\Framework\Session;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\FileToLargeException;
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

            $this->service->editHome($_POST['title'], $_POST['subtitle'], $_FILES['topper_image']);
        }
        catch(Exception $ex){    
            Session::set('temp_error', "Something went wrong try again later." . $ex->getMessage());
        }

        header('location: /cms/yummy');
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

            $this->service->editList($_POST['title'], $_POST['subtitle'], $_FILES['topper_image']);
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
        $this->requireAdmin();

        try{    
            if($this->service->editRestaurant($_POST, $_FILES)){
                Session::set('temp_success', "Successfully edited restaurant.");
            }         

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }   
        catch(Exception $ex){
            Session::set('temp_error', "Restaurant edit failed! Something went wrong, try again later.");
        }

        if(isset($_POST['restaurant_id'])){
            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
        }
        else{
            header('location: /cms/yummy/restaurant-list');
        }
    }

    public function addImage(){
        $this->requireAdmin();

        /*$inipath = php_ini_loaded_file();

        if ($inipath) {
            echo 'Loaded php.ini: ' . $inipath;
        } else {
        echo 'A php.ini file is not loaded';
        }*/

        try{
            if(!isset($_POST['restaurant_id']) || !isset($_FILES['image_add'])) throw new EmptyFieldException(); 

            if($this->service->addRestaurantImage($_POST['restaurant_id'], $_FILES['image_add'])){
                Session::set('temp_success', "Successfully added new image to restaurant.");
            } 

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }
        catch(FileToLargeException $ex){
            Session::set('temp_error', "Faild to add image to restaurant. The file size is too big. Max file size is 8 megabytes.");
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

        if(isset($_POST['restaurant_id'])){
            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
        }
        else{
            header('location: /cms/yummy/restaurant-list');
        }
    }

    public function deleteImage(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['restaurant_id']) || !isset($_POST['image_id'])) throw new EmptyFieldException(); 
                    
            if($this->service->removeRestaurantImage($_POST['image_id'])){
                Session::set('temp_success', "Successfully deleted image of restaurant.");
            } 

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }
        catch(Exception $ex){
            Session::set('temp_error', "Image delete failed! Something went wrong, try again later.");
        }

        if(isset($_POST['restaurant_id'])){
            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
        }
        else{
            header('location: /cms/yummy/restaurant-list');
        }
    }
}