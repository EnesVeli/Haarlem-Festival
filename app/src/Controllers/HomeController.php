<?php
namespace App\Controllers;

use App\Services\EventService;

class HomeController
{
    public function index()
    {
        
        
        $eventService = new EventService();
        $events = $eventService->getHomepageEvents();

        require __DIR__ . '/../Views/home.php';
    }
}