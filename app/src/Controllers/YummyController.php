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
}