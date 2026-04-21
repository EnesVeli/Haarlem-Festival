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
        $this->order_service = new OrderService();
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

                foreach($order->order_items as $item){
                    $this->formatOrderItem($item);

                    // format price
                    $item->price_string = number_format(((float)$item->price) / 100, 2);
                }
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

    private function formatOrderItem(OrderItem $item){
        switch($item->booking_type){
            case BookingType::Yummy:
                // format date
                $item->date_string = $item->booking->reservation_time_slot->date->format('D, M j');
            
                // format time
                $time_start = $item->booking->reservation_time_slot->time;

                $time_end = clone $time_start;
                $time_end->add(new DateInterval('PT' . $item->booking->reservation_time_slot->duration . 'M'));

                $item->time_string = $time_start->format('H:i') . ' - ' . $time_end->format('H:i'); 
                break;
            case BookingType::History:
                // format date
                $item->date_string = $item->booking->date->format('D, M j');
            
                // format time
                $time_start = $item->booking->date;

                $time_end = clone $time_start;
                $time_end->add(new DateInterval('PT' . OrderService::$HISTORY_ROUTE_DURATION . 'M'));

                $item->time_string = $time_start->format('H:i') . ' - ' . $time_end->format('H:i'); 
                break;
            case BookingType::Stories:
                // format date
                $item->date_string = (new DateTime($item->booking->event->start_time))->format('D, M j');
            
                // format time
                $time_start = new DateTime($item->booking->event->start_time);

                $time_end = new DateTime($item->booking->event->end_time);

                $item->time_string = $time_start->format('H:i') . ' - ' . $time_end->format('H:i'); 
                break;
        }
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
}
