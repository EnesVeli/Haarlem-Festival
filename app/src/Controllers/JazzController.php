<?php
namespace App\Controllers;

use App\Services\JazzService;
use App\Framework\Session;

class JazzController
{
    public function index()
    {
        $service = new JazzService();
        $data = $service->getHomePageData();

        $experiences = $data['experiences'];
        $performers = $data['performers'];
        $recommendations = $data['recommendations'];

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

    public function performer()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Performer not found';
            return;
        }

        $service = new JazzService();
        $performer = $service->getPerformerById($id);

        if (!$performer) {
            http_response_code(404);
            echo '404 - Performer not found';
            return;
        }
        
        $currentUser = Session::user();
        require __DIR__ . '/../Views/jazz/performer.php';
    }
}