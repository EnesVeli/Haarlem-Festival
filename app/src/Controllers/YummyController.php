<?php
namespace App\Controllers;

use App\Repositories\YummyRestaurantsRepository;
use App\Services\YummyService;

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

        $view_model = $this->service->getHomeViewModel();

        require __DIR__ . '/../Views/yummy/home.php';
    }

    public function list(){
        $pageTitle = 'Yummy - Restaurant List';

        $view_model = $this->service->getListViewModel($_GET['place_type'] ?? null, $_GET['meal_type'] ?? null, $_GET['food_type'] ?? null, $_GET['cuisine_type'] ?? null, $_GET['sorting'] ?? null, $_GET['page'] ?? null);

        require __DIR__ . '/../Views/yummy/list.php';
    }
}