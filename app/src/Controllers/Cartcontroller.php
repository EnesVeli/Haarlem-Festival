<?php
namespace App\Controllers;

use App\Enums\BookingType;
use App\Enums\OrderStatus;
use App\Framework\Session;
use App\Models\Exceptions\EmptyCartException;
use App\Models\Exceptions\EmptyPostException;
use App\Models\OrderItem;
use App\Services\OrderService;
use App\ViewModels\Cart\CartViewModel;
use DateInterval;
use DateTime;
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
        }

        $error_message = Session::popTempError();

        try{
            $order = $this->order_service->getOrderWithOrderItemsByUserId(Session::user()['user_id']);

            if($order != null){
                $view_model = new CartViewModel();
                $view_model->order = $order;

                $subtotal = $this->order_service->calcOrderSubtotalPrice($view_model->order) / 100;
                $total = $subtotal * (OrderService::$VAT_RATE + 10000) / 10000;

                $view_model->sub_total = number_format($subtotal, 2);
                $view_model->total = number_format($total, 2);
                $view_model->vat_cost = number_format($total - $subtotal, 2);
                $view_model->vat_persent = number_format(OrderService::$VAT_RATE / 100, 2);
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
        }

        try{
            $user_id = Session::user()['user_id'];

            if($user_id == null || $_POST['order_id'] == null || $_POST['item_id'] == null) throw new EmptyPostException();        

            $this->order_service->removeOrderItemFromCart($_POST['order_id'], $_POST['item_id'], $user_id);
        }
        catch(Exception $ex){
            Session::setTempError("Failed to remove cart item. Something went wrong, try again later." . $ex->getMessage());
        }

        header("location: /cart");
    }

    public function complete(){
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Your session has expired. Log in, in order to modify your cart.");
            header("Location: /login");
        }

        $this->order_service->completeOrder(Session::user()['user_id']);
        
        try{
            
        }
        catch(Exception $ex){
            Session::setTempError("Failed to complete order." . $ex->getMessage());
        }

        header("Location: /cart");
    }
}
