<?php

namespace App\Controllers;

use App\Services\Jazz\JazzService;
use App\Framework\Session;
use App\ViewModels\Jazz\JazzHomeViewModel;
use App\ViewModels\Jazz\JazzPerformerViewModel;

class JazzController
{
    public function index()
    {
        $service = new JazzService();
        $data = $service->getHomePageData();

        $vm = new JazzHomeViewModel(
            $data['hero'],
            $data['intro'],
            $data['experiences'],
            $data['performers'],
            $data['recommendations'],
            $data['locations'],
            Session::user()
        );

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
        $data = $service->getPerformerDetail($id);

        if (!$data) {
            http_response_code(404);
            echo '404 - Performer not found';
            return;
        }

        $vm = new JazzPerformerViewModel(
            $data['performer'],
            $data['appearances'],
            $data['highlights'],
            $data['tracks'],
            $data['locations'],
            $data['recommendations'],
            Session::user()
        );

        require __DIR__ . '/../Views/jazz/performer.php';
    }
    public function experiences(): void
{
    $repo = new \App\Repositories\JazzRepository();
    $experiences = $repo->getExperiences();

    $pageTitle = 'Jazz CMS - Experiences';
    $pageCSS = 'jazz.css';
    $user = \App\Framework\Session::user();

    require __DIR__ . '/../../../Views/cms/jazz/experiences.php';
}
}