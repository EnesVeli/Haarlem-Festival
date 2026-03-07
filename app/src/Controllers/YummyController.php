<?php
namespace App\Controllers;

use App\Services\YummyService;

class YummyController
{
    public function index()
    {
        $pageTitle = 'Yuumy - Haarlem Festival';

        $service = new YummyService();

        $guides = $service->getActiveGuides();
        $restaurants = $service->getActiveRestaurants();

        require __DIR__ . '/../Views/yummy/home.php';
    }

    public function list(){
        $service = new YummyService();

        // Get Filtering and Sorting
        $place_type = isset($_GET['place_type']) ? explode(',', $_GET['place_type']) : [];
        $meal_type = isset($_GET['meal_type']) ? explode(',', $_GET['meal_type']) : [];
        $food_type = isset($_GET['food_type']) ? explode(',', $_GET['food_type']) : [];
        $cuisine_type = isset($_GET['cuisine_type']) ? explode(',', $_GET['cuisine_type']) : [];

        $sorting = $_GET['sorting'] ?? '';

        $restaurants = $service->getRestaurantFiltered($place_type, $meal_type, $food_type, $cuisine_type, $sorting);

        // Load types form db
        $all_types = $service->getTypes();

        $pl_types = [];
        $ml_types = [];
        $fd_types = [];
        $cs_types = []; 

        for($i = 0; $i < count($all_types); $i++){ 
            switch($all_types[$i]['category']){
                case 0:
                    $pl_types[] = $all_types[$i];
                    break;
                case 1:
                    $ml_types[] = $all_types[$i];
                    break;
                case 2:
                    $fd_types[] = $all_types[$i];
                    break;
                case 3:
                    $cs_types[] = $all_types[$i];
                    break;
            }
        }      

        require __DIR__ . '/../Views/yummy/list.php';
    }
}