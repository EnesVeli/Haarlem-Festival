<?php
namespace App\Controllers;

use App\Services\JazzService;
use App\Framework\Session;

class JazzController
{
    public function index()
    {
        $service = new JazzService();
        $experiences = $service->getExperiences();
        $performers = $service->getPerformers();
        $recommendations = $service->getEventRecommendationsForJazz();

        $currentUser = Session::user();

        require __DIR__ . '/../Views/jazz/home.php';
    }

    public function schedule()
    {
        require __DIR__ . '/../Views/jazz/schedule.php';
    }

    public function tickets()
    {
        require __DIR__ . '/../Views/jazz/tickets.php';
    }
}