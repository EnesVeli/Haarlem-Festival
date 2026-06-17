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

    public function getCsrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public function index(): void
    {
        $errorMessage = Session::popTempError();

        try{
            $timeSlots = $this->service->getAllTimeSlots();
            if($timeSlots === null) throw new QueryExecutionException();

            $viewModel = new HistoryIndexViewModel(
                $this->service->getHighlights(),
                $timeSlots,
                $this->service->getContent(),
                HistoryService::getMaxDateOffset()
            );
        }
        catch(Exception $ex){
            $errorMessage = 'Something went wrong. Try again later.';
            $viewModel = new HistoryIndexViewModel([], [], [], HistoryService::getMaxDateOffset());
        }

        require __DIR__ . '/../Views/history/index.php';
    }

    public function detail($vars): void
    {
        try {
            $slug = $vars['slug'] ?? '';
            $pageData = $this->service->getDetailPage($slug);
        } catch (Exception $ex) {
            Session::setTempError("Something went wrong. Try again later.");
            header('Location: /history');
            exit;
        }

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

        $csrfToken = $this->getCsrfToken();
        $errorMessage = Session::popTempError();

        try{
            if($reservation_id == null){
                if(!isset($_GET['slot']) || !isset($_GET['offset']) || !is_numeric($_GET['slot']) || !is_numeric($_GET['offset'])) {
                    throw new EmptyPostException();
                }

                $slotId = (int)$_GET['slot'];
                $offset = (int)$_GET['offset'];
                if($offset < 0 || $offset > HistoryService::getMaxDateOffset()) {
                    throw new PostMismatchException();
                }

                $reservation = $this->service->getOrCreateReservation($slotId, $offset);
                if($reservation == null) throw new PostMismatchException("Failed to find reservation slot by slot_id and date_offset");
            }
            else{
                $reservation = $this->service->getHistoryReservationSlotById($reservation_id);
                if($reservation == null) throw new PostMismatchException("Failed to find reservation slot by its id");
            }

            $timeSlot = $this->service->getHistoryTimeSlotById($reservation->slot_id);
            if($timeSlot == null) throw new PostMismatchException("No history time slot with given id.");

            $view_model = new HistoryBookViewModel();
            $view_model->reservation_id = $reservation->reservation_id;
            $view_model->date = $reservation->date->format("d.m.Y - F j");
            $view_model->time = $timeSlot->time->format("H:i");
            $view_model->individual_cost = $this->service->getIndividualPrice();
            $view_model->family_cost = $this->service->getFamilyPrice();

            require __DIR__ . '/../Views/history/booking.php';
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. Try again later.");
        }

        header("Location: /history");
    }

    public function book(): void 
    {
        if(!$this->isLoggedIn()){
            Session::setTempError("In order to book a route, you must login first.");
            header("Location: /login");
            exit;
        } 

        try{
            $token = $_POST['_csrf_token'] ?? '';
            $sessionToken = $_SESSION['_csrf_token'] ?? '';
            if ($token === '' || $token !== $sessionToken) {
                throw new Exception('CSRF token validation failed.');
            }

            if(!isset($_POST['reservation_id'], $_POST['individual_count'], $_POST['family_count'], $_POST['language']) ||
               !is_numeric($_POST['reservation_id']) || !is_numeric($_POST['individual_count']) || !is_numeric($_POST['family_count'])) {
                throw new EmptyPostException();
            }

            $booking = new HistoryBooking();
            $booking->reservation_id = (int)$_POST['reservation_id'];
            $booking->language = trim((string)$_POST['language']);
            $booking->individual_count = max(0, (int)$_POST['individual_count']);
            $booking->family_count = max(0, (int)$_POST['family_count']);

            $this->service->bookHistoryBooking($booking, Session::user()['user_id']);

            header("Location: /cart");
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. Try again later.");
            
            if(isset($_POST['reservation_id']) && is_numeric($_POST['reservation_id'])) {
                header("Location: /history/booking?reservation_id=" . (int)$_POST['reservation_id']);
            } else {
                header("Location: /history");
            }
            exit;
        }
    }
}