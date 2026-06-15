<?php

namespace App\Controllers;

use App\Framework\Session;
use App\Interfaces\Services\IJazzService;
use App\Models\Exceptions\EmptyPostException;
use App\Services\Jazz\JazzService;
use App\ViewModels\Jazz\JazzHomeViewModel;
use App\ViewModels\Jazz\JazzPerformerViewModel;
use Exception;
use Throwable;

class JazzController extends BaseController
{
    private IJazzService $service;

    public function __construct()
    {
        $this->service = JazzService::getInstance();
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
                Session::currentUser()
            );

            $this->render('jazz/home', [
                'vm' => $vm,
                'error_message' => Session::popTempError(),
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
                Session::currentUser()
            );
    
            $this->render('jazz/performer', [
                'vm' => $vm,
                'error_message' => Session::popTempError(),
                'pageTitle' => 'Jazz Performer',
                'pageCSS' => 'jazz.css',
                'mainClass' => 'jazz-main',
                'user' => Session::user()
            ]);
        } catch (Throwable $error) {
            Session::setTempError("Something went wrong. Try again later.");
            header('Location: /jazz');
            exit;
        }
    }

    public function booking(): void {
        if(!$this->isLoggedIn()){
            Session::setTempError("Login first, in order to book a ticket.");
            header("Location: /login");
            exit;
        }

        $perf = $this->service->getPerformerById((int)($_GET['perf'] ?? 0));

        if ($perf === null) {
            header('Location: /jazz');
            exit;
        }

        $this->render('jazz/book', [
            'perf' => $perf,
            'error_message' => Session::popTempError(),
            'pageTitle' => 'Jazz Performer',
            'pageCSS' => 'jazz.css',
            'mainClass' => 'jazz-main',
            'user' => Session::user()
        ]);
    }

    public function book(): void {
        if(!$this->isLoggedIn()){
            Session::setTempError("Login first, in order to book a ticket.");
            header("Location: /login");
            exit;
        }

        try{
            if(!isset($_POST['performer_id']) || !isset($_POST['quantity'])) throw new EmptyPostException();

            $this->service->bookTickets((int)$_POST['performer_id'], (int)$_POST['quantity'], Session::currentUser()->user_id);

            header('Location: /cart');
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Failed to book. Please try again later.");
        }

        if (!empty($_POST['performer_id'])) {
            header('Location: /jazz/book?perf=' . (int)$_POST['performer_id']);
        } else {
            header('Location: /jazz');
        }
        exit;
    }
}