<?php
namespace App\Controllers;

use App\Services\HomeService;
use App\ViewModels\HomeViewModel;
use Exception;

class HomeController
{
    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = HomeService::getInstance();
    }

    public function index(): void
    {
        $error_message = null;

        try {
            $viewModel = new HomeViewModel(
                $this->homeService->getHomeContent(),
                $this->homeService->getHomeEvents()
            );
        } catch (Exception $exception) {
            $error_message = 'Something went wrong. Please try again later.';
            $viewModel = new HomeViewModel([], []);
        }

        require __DIR__ . '/../Views/home.php';
    }
}
