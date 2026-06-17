<?php

namespace App\Controllers\Cms\Yummy;

use App\Controllers\Cms\BaseCmsController;
use App\Framework\Session;
use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\FileToLargeException;
use App\Models\Exceptions\MaxCountExceededException;
use App\Models\Exceptions\RestaurantAlreadyHasTagException;
use App\Services\Yummy\YummyCmsService;
use App\ViewModels\Yummy\Cms\YummyAddViewModel;
use App\ViewModels\Yummy\Cms\YummyHomeViewModel;
use App\ViewModels\Yummy\Cms\YummyListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantViewModel;
use App\ViewModels\Yummy\Cms\YummyTopper;
use Exception;
use Uri\InvalidUriException;

class AdminYummyController extends BaseCmsController {
    private YummyCmsService $service;

    public function __construct(){
        $this->service = YummyCmsService::getInstance(); 
    }

    public function index(){
        $this->requireAdmin();

        $error_message = Session::popTempError();

        try{
            $view_model = new YummyHomeViewModel();

            $view_model->topper = new YummyTopper();
            $view_model->topper->title = "Yummy CMS - Home";
            $view_model->topper->subtitle = "Manage yummy home page.";
            $view_model->topper->button_text = "View page";
            $view_model->topper->button_link = '/yummy';
            $view_model->topper->active_tab = 0;

            $home_data = $this->service->getHomeData();

            $view_model->home_title = $home_data['home_title'];
            $view_model->home_subtitle = $home_data['home_subtitle'];
            $view_model->topper_path = $home_data['home_image'];           
        }
        catch(Exception $ex){
            $error_message = "Something went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/index.php';
    }

    public function editHome(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle']) || !isset($_FILES['topper_image'])) throw new EmptyFieldException();          

            $this->service->editHome($_POST['title'], $_POST['subtitle'], $_FILES['topper_image']);
        }
        catch(Exception $ex){    
            Session::setTempError("Something went wrong try again later.");
        }

        header('location: /cms/yummy');
    }

    public function list(){
        $this->requireAdmin();

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();

        try{
            $view_model = new YummyListViewModel();

            $view_model->topper = new YummyTopper();
            $view_model->topper->title = "Yummy CMS - List";
            $view_model->topper->subtitle = "Manage yummy restaurant list page.";
            $view_model->topper->button_text = "View page";
            $view_model->topper->button_link = '/yummy/list';
            $view_model->topper->active_tab = 1;

            $home_data = $this->service->getListData();

            $view_model->list_title = $home_data['list_title'];
            $view_model->list_subtitle = $home_data['list_subtitle'];
            $view_model->list_image = $home_data['list_image'];
        }
        catch(Exception $ex){
            $error_message = "Something went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/list.php';
    }

    public function editList(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle']) || !isset($_FILES['topper_image'])) throw new EmptyFieldException();                    

            $this->service->editList($_POST['title'], $_POST['subtitle'], $_FILES['topper_image']);
        }
        catch(Exception $ex){    
            Session::setTempError("Something went wrong try again later.");
        }

        header('location: /cms/yummy/list');
    }

    public function restaurantList(){
        $this->requireAdmin();

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();

        try{
            // Check parameters
            $sort = $_GET['sort'] ?? 0;
            $page = $_GET['page'] ?? 0;
            $order = $_GET['order'] ?? 0;

            if(!is_numeric($sort) || !is_numeric($page) || !is_numeric($order)) throw new InvalidUriException("Invalid uri parameters.");

            if($sort < 0 || $sort > 4 || $page < 0) throw new InvalidUriException("Invalid uri parameters.");

            if($sort < 0 || $sort > 4 || $page < 0) throw new InvalidUriException("Invalid uri parameters.");

            // Create view model
            $view_model = new YummyRestaurantListViewModel();

            // Load restaurants
            $view_model->restaurants = $this->service->getRestaurantList($sort, $order, $page);

            // Put params into view model
            $view_model->cur_page = $page;
            $view_model->sort_field = $sort;
            $view_model->sort_order = $order; 

            // Calculate offset and limit for page number selection
            $count = $this->service->countRestaurants();  

            $this->service->fillInRestaurantViewModelPagination($view_model, $count);

            // Setup topper
            $view_model->topper = new YummyTopper();
            $view_model->topper->title = "Yummy CMS - Restaurants";
            $view_model->topper->subtitle = "Manage yummy restaurants.";
            $view_model->topper->button_text = "View restaurants";
            $view_model->topper->button_link = '/yummy/list';
            $view_model->topper->active_tab = 2;
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

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();

        try{
            if(!isset($_GET['id']) || !is_numeric($_GET['id'])) throw new InvalidUriException();
            $restaurant_id = $_GET['id'];

            $view_model = new YummyRestaurantViewModel();

            $view_model->res = $this->service->getRestaurantById($restaurant_id);
            $view_model->hours = $this->service->getRestaurantOpeningHours($restaurant_id);
            $view_model->images = $this->service->getRestaurantImages($restaurant_id);
            $view_model->types = $this->service->getRestaurantTypes($restaurant_id);

            $view_model->all_types = $this->service->getAllTypes($view_model->types);


            // Setup topper
            $view_model->topper = new YummyTopper();
            $view_model->topper->title = "Yummy CMS - Restaurant - " . $view_model->res->name;
            $view_model->topper->subtitle = "Manage yummy restaurant.";
            $view_model->topper->button_text = "View restaurant";
            $view_model->topper->button_link = '/yummy/restaurant?id=' . $view_model->res->restaurant_id;
            $view_model->topper->active_tab = -1;
        }
        catch(Exception $ex){
            $error_message = "Something went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/restaurant.php';
    }

    public function editRestaurant(){
        $this->requireAdmin();

        try{    
            if($this->service->editRestaurant($_POST, $_FILES)){
                Session::setTempSuccess("Successfully edited restaurant.");
            }         

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }   
        catch(Exception $ex){
            Session::setTempError("Restaurant edit failed! Something went wrong, try again later.");
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

        try{
            if(!isset($_POST['restaurant_id']) || !isset($_FILES['image_add'])) throw new EmptyFieldException(); 

            if($this->service->addRestaurantImage($_POST['restaurant_id'], $_FILES['image_add'])){
                Session::setTempSuccess("Successfully added new image to restaurant.");
            } 

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }
        catch(FileToLargeException $ex){
            Session::setTempError("Faild to add image to restaurant. The file size is too big. Max file size is 8 megabytes.");
        }
        catch(MaxCountExceededException $ex){
            Session::setTempError("The maximum count of additional images for restaurant is 11. Delete some images to add a new one's.");
        }
        catch(EmptyFieldException $ex){
            Session::setTempError("You must upload an image in order to add additional images to restaurant.");
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong try again later.");
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
                Session::setTempSuccess("Successfully deleted image of restaurant.");
            } 

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Image delete failed! Something went wrong, try again later.");
        }

        if(isset($_POST['restaurant_id'])){
            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
        }
        else{
            header('location: /cms/yummy/restaurant-list');
        }
    }

    public function addTag(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['restaurant_id']) || !isset($_POST['tag_id'])) throw new EmptyFieldException(); 

            if($this->service->addRestaurantType($_POST['restaurant_id'], $_POST['tag_id'])){
                Session::setTempSuccess("Successfully added tag to restaurant.");
            } 

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }
        catch(RestaurantAlreadyHasTagException $ex){
            Session::setTempError("Faild to add tag to restaurant. Restaurant already has selected tag.");
        }
        catch(DBAccessException $ex){
            Session::setTempError("Faild to add tag to restaurant. Something went wrong try again later.");
        }
        catch(Exception $ex){
            Session::setTempError("Faild to add tag to restaurant. Something went wrong try again later.");
        }

        if(isset($_POST['restaurant_id'])){
            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
        }
        else{
            header('location: /cms/yummy/restaurant-list');
        }
    }

    public function deleteTag(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['restaurant_id']) || !isset($_POST['type_id'])) throw new EmptyFieldException(); 

            if($this->service->deleteRestaurantTag($_POST['restaurant_id'], $_POST['type_id'])){
                Session::setTempSuccess("Successfully removed tag to restaurant.");
            } 

            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Failed to remove tag from restaurant. Something went wrong try again later.");
        }

        if(isset($_POST['restaurant_id'])){
            header('location: /cms/yummy/restaurant?id=' . $_POST['restaurant_id']);
        }
        else{
            header('location: /cms/yummy/restaurant-list');
        }
    }

    public function restaurantAdd(){
        $this->requireAdmin();

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();

        try{
            $view_model = new YummyAddViewModel();

            $view_model->topper = new YummyTopper();
            $view_model->topper->title = 'Yummy CMS - New Restaurant';
            $view_model->topper->subtitle = "Create a new yummy restaurant.";
            $view_model->topper->button_text = "View list";
            $view_model->topper->button_link = '/cms/yummy/restaurant-list';
            $view_model->topper->active_tab = 3;
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong try again later.");
        }

        require __DIR__ . '/../../../Views/cms/yummy/restaurant-add.php';
    }

    public function addRestaurant(){
        $this->requireAdmin();

        try{
            $this->service->addRestaurant($_POST, $_FILES);

            Session::setTempSuccess("Successfully added new restaurant.");      

            header('location: /cms/yummy/restaurant-list');
            exit;
        }
        catch(RestaurantAlreadyHasTagException $ex){
            Session::setTempError("Faild to add tag to restaurant. Restaurant already has selected tag." . $ex->getMessage());
        }
        catch(DBAccessException $ex){
            Session::setTempError("Faild to add tag to restaurant. Something went wrong try again later." . $ex->getMessage());
        }
        catch(Exception $ex){
            Session::setTempError("Faild to add tag to restaurant. Something went wrong try again later." . $ex->getMessage());
        }

        header('location: /cms/yummy/restaurant/add');     
    } 
}