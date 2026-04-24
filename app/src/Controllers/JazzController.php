<?php

namespace App\Controllers;

use App\Framework\Session;
use App\Services\Jazz\JazzService;
use App\ViewModels\Jazz\JazzHomeViewModel;
use App\ViewModels\Jazz\JazzPerformerViewModel;
use Throwable;

class JazzController extends BaseController
{
    private JazzService $service;

    public function __construct(?JazzService $service = null)
    {
        $this->service = $service ?? new JazzService();
    }

    public function index(): void
    {
        try {
            $data = $this->service->getHomePageData();

            $vm = new JazzHomeViewModel(
                $data['hero'],
                $data['intro'],
                $data['experiences'],
                $data['performers'],
                $data['recommendations'],
                $data['locations'],
                Session::user()
            );

            $this->render('jazz/home', [
                'vm' => $vm,
                'pageTitle' => 'Haarlem Jazz',
                'pageCSS' => 'jazz.css',
                'mainClass' => 'jazz-main',
                'user' => Session::user()
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo 'Something went wrong while loading the Jazz page.';
        }
    }

    public function schedule(): void
    {
        $this->render('jazz/schedule', [
            'pageTitle' => 'Jazz Schedule',
            'pageCSS' => 'jazz.css',
            'mainClass' => 'jazz-main',
            'user' => Session::user()
        ]);
    }

    public function tickets(): void
    {
        $this->render('jazz/tickets', [
            'pageTitle' => 'Jazz Tickets',
            'pageCSS' => 'jazz.css',
            'mainClass' => 'jazz-main',
            'user' => Session::user()
        ]);
    }

    public function performer(): void
    {
        try {
            $id = (int)($_GET['id'] ?? 0);
    
            if ($id <= 0) {
                http_response_code(404);
                echo '404 - Performer not found';
                return;
            }
    
            $data = $this->service->getPerformerDetail($id);
    
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
    
            $this->render('jazz/performer', [
                'vm' => $vm,
                'pageTitle' => 'Jazz Performer',
                'pageCSS' => 'jazz.css',
                'mainClass' => 'jazz-main',
                'user' => Session::user()
            ]);
        } catch (Throwable $error) {
            http_response_code(500);
            echo $error->getMessage();
        }
    }

    public function booking(){
        $this->render('jazz/book', [
            'perf' => $this->service->getPerformerById($_GET['perf']),
            'pageTitle' => 'Jazz Performer',
            'pageCSS' => 'jazz.css',
            'mainClass' => 'jazz-main',
            'user' => Session::user()
        ]);
    }
}