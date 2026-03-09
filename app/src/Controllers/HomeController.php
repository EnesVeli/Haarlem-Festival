<?php
namespace App\Controllers;

use App\Services\HomeService;

class HomeController
{
    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = new HomeService();
    }

    public function index(): void
    {
        $homeContent = $this->homeService->getHomeContent();
        $eventCards  = $this->homeService->getHomeEvents(); 
        $venueList   = $this->homeService->getVenueList();

        require __DIR__ . '/../Views/home.php';
    }
}