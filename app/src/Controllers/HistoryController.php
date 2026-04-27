<?php

namespace App\Controllers;

use App\Framework\Session;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\PostMismatchException;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\History\HistoryBooking;
use App\Services\HistoryService;
use App\Services\OrderService;
use App\ViewModels\History\HistoryBookViewModel;
use App\ViewModels\History\HistoryIndexViewModel;
use App\ViewModels\History\HistoryDetailViewModel;
use Exception;

class HistoryController extends BaseController
{
    private HistoryService $service;

    public function __construct()
    {
        $this->service = HistoryService::getInstance();
    }

    public function index()
    {
        $error_message = Session::popTempError();

        try{
            $time_slots = $this->service->getAllTimeSlots();
            if($time_slots == null) throw new QueryExecutionException();

            $viewModel = new HistoryIndexViewModel(
                $this->service->getHighlights(),
                $time_slots,
                $this->service->getContent(),
                HistoryService::$max_date_offset
            );
        }
        catch(Exception $ex){
            $error_message = 'Something went wrong. Try again later.';
        }

        require __DIR__ . '/../Views/history/index.php';
    }

    public function detail($vars)
    {
        $slug     = $vars['slug'] ?? '';
        $pageData = $this->service->getDetailPage($slug);

        if (!$pageData) {
            header('Location: /history');
            exit;
        }

        $viewModel = new HistoryDetailViewModel(
            $pageData['detail'],
            $pageData['sections'],
            $pageData['gallery'],
            $pageData['facts'],
            $this->service->getOtherHighlights($slug)
        );

        require __DIR__ . '/../Views/history/detail.php';
    }

    public function booking(?array $args, ?int $reservation_id = null): void
    {
        if(!$this->isLoggedIn()){
            Session::setTempError("In order to book a route, you must login first.");
            header("Location: /login");
            exit;
        }

        $error_message = Session::popTempError();

        try{
            if($reservation_id == null){
                if(!isset($_POST['slot_id']) || !isset($_POST['date_offset'])) throw new EmptyPostException();

                if($_POST['date_offset'] < 0 || $_POST['date_offset'] > HistoryService::$max_date_offset) throw new PostMismatchException();

                $reservation = $this->service->getHistoryReservationBySlotIdAndDateOffset($_POST['slot_id'], $_POST['date_offset']);
                if($reservation == null) throw new PostMismatchException("Failed to find reservation slot by slot_id and date_offset");
            }
            else{
                $reservation = $this->service->getHistoryReservationSlotById($reservation_id);
                if($reservation == null) throw new PostMismatchException("Failed to find reservation slot by its id");
            }

            $time_slot = $this->service->getHistoryTimeSlotById($reservation->slot_id);
            if($time_slot == null) throw new PostMismatchException("No history time slot with given id.");

            $view_model = new HistoryBookViewModel();
            $view_model->reservation_id = $reservation->reservation_id;
            $view_model->date = $reservation->date->format("d.m.Y - F j");
            $view_model->time = $time_slot->time->format("H:i");
            $view_model->individual_cost = $this->service->getIndividualPrice();
            $view_model->family_cost = $this->service->getFamilyPrice();

            require __DIR__ . '/../Views/history/booking.php';
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. Try again later.");
        }

        header("location: /history");
    }

    public function book() : void 
    {
        if(!$this->isLoggedIn()){
            Session::setTempError("In order to book a route, you must login first.");
            header("Location: /login");
            exit;
        } 

        try{
            if(!isset($_POST['reservation_id']) || !isset($_POST['individual_count']) || !isset($_POST['family_count']) || !isset($_POST['language'])) throw new EmptyPostException();

            $booking = new HistoryBooking();
            $booking->reservation_id = $_POST['reservation_id'];
            $booking->language = $_POST['language'];
            $booking->individual_count = $_POST['individual_count'];
            $booking->family_count = $_POST['family_count'];

            $this->service->bookHistoryBooking($booking, Session::user()['user_id']);

            header("location: /cart");
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. Try again later." . $ex->getMessage());
        }

        if(isset($_POST['reservation_id'])){
            $this->booking(null, $_POST['reservation_id']);
        }
        else{
            header("location: /history");
        }  
    }
}