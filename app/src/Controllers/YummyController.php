<?php
namespace App\Controllers;

use App\Repositories\YummyRestaurantsRepository;
use App\Services\YummyService;
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
}