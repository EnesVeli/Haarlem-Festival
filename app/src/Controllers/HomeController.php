<?php
namespace App\Controllers;

use App\Services\HomeService;
use App\ViewModels\HomeViewModel;

class HomeController
{
    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = HomeService::getInstance();
    }

    public function index(): void
    {
        $viewModel = new HomeViewModel(
            $this->homeService->getHomeContent(),
            $this->homeService->getHomeEvents(),
            $this->homeService->getVenueList()
        );

        require __DIR__ . '/../Views/home.php';
    }
}