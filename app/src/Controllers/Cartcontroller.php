<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\PostMismatchException;
use App\Services\OrderService;
use App\ViewModels\Cart\CartViewModel;
use App\ViewModels\Cart\PersonalProgramViewModel;
use Exception;

class CartController extends BaseController
{
    private OrderService $order_service;

    public function __construct()
    {
        $this->order_service = OrderService::getInstance();
    }

    public function index() : void
    {
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Log in, in order to view your cart.");
            header("Location: /login");
            exit;
        }

        $error_message = Session::popTempError();

        try{
            $order = $this->order_service->getOrderWithOrderItemsByUserId(Session::user()['user_id']);

            if($order != null){
                $view_model = new CartViewModel();
                $view_model->order = $order;
                $view_model->total = number_format($this->order_service->calcOrderTotalCents($view_model->order) / 100, 2);
            }
            else{
                $view_model = null;
            }        
        }
        catch(Exception $ex){
            $error_message = "Something went wrong, try again later.";
        }

        require __DIR__ . '/../Views/cart/index.php';
    }

    public function remove(){
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to modify your cart.");
            header("Location: /login");
            exit;
        }

        try{
            $user_id = Session::user()['user_id'];

            if($user_id == null || $_POST['order_id'] == null || $_POST['item_id'] == null) throw new EmptyPostException();        

            $this->order_service->removeOrderItemFromCart($_POST['order_id'], $_POST['item_id'], $user_id);
        }
        catch(Exception $ex){
            Session::setTempError("Failed to remove cart item. Something went wrong, try again later.");
        }

        header("location: /cart");
    }

    public function checkout(){
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to complete your order.");
            header("Location: /login");
            exit;
        }

        try{
            $stripe_session = $this->order_service->startOrderPayment(Session::user()['user_id']);

            header('Location: ' . $stripe_session->url);
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Failed to complete order.");
        }

        header("Location: /cart");
    }

    public function program(){
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to view your personal program.");
            header("Location: /login");
            exit;
        }       

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();
        
        try{
            $view_model = new PersonalProgramViewModel();      
            $view_model->orders = $this->order_service->getOrdersPersonalProgram(Session::user()['user_id']);
        }
        catch(Exception $ex){
            Session::setTempError("Failed to get order information. Try again later.");
        }

        require __DIR__ . '/../Views/cart/personal_program.php';
    }

    public function payment(){
        try{
            if(!isset($_GET['session_id']) || !isset($_GET['order_id'])) throw new EmptyPostException();

            $this->order_service->finishOrderPayment($_GET['session_id'], $_GET['order_id']);

            require __DIR__ . '/../Views/cart/payment-success.php';
            exit;
        }
        catch(EmptyPostException $ex){
            Session::setTempError("Incorrect url parameters.");
        }
        catch(PostMismatchException $ex){
            Session::setTempError("Incorrect url parameters.");
        }
        catch(Exception $ex){
            Session::setTempError("Failed to finish order.");
        }

        header('location: /program');
    }

    public function paymentFail(){
        require __DIR__ . '/../Views/cart/payment-fail.php';
    }

    public function paymentNotPaidCancel(){
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to cancel your orders.");
            header("Location: /login");
            exit;
        }     

        try{
            if(!isset($_POST['order_id'])) throw new EmptyPostException();

            $this->order_service->cancelNotPaidOrder($_POST['order_id'], Session::user()['user_id']);

            Session::setTempSuccess("Successfully cancelled order.");
        }
        catch(Exception $ex){
            Session::setTempError("Failed to cancel order.");
        }

        header('location: /program');
    }

    public function paymentNotPaidPay(){
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to finish your order.");
            header("Location: /login");
            exit;
        }     

        try{
            if(!isset($_POST['order_id'])) throw new EmptyPostException();

            $stripe_session = $this->order_service->restartOrderPayment($_POST['order_id'], Session::user()['user_id']);

            header('Location: ' . $stripe_session->url);
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Failed to finish order. Try again later.");
        }

        header('location: /program');
    }

    /*public function paymentPaidCancel(){
        return;

        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to cancel your orders.");
            header("Location: /login");
            exit;
        }     

        try{
            if(!isset($_POST['order_id'])) throw new EmptyPostException();

            $this->order_service->cancelPaidOrder($_POST['order_id'], Session::user()['user_id']);

            header('Location: ');
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Failed to finish order. Try again later.");
        }

        header('location: /program');
    }*/
}
