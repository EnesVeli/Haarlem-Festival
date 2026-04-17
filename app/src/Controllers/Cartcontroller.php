<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Services\OrderService;
use App\ViewModels\Cart\CartViewModel;
use DateInterval;
use Exception;

class CartController extends BaseController
{
    private OrderService $order_service;

    public function __construct()
    {
        $this->order_service = new OrderService();
    }

    public function index(): void
    {
        if(!$this->isLoggedIn())
        {
            Session::setTempError("Log in, in order to view your cart.");
            header("Location: /login");
        }

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
                    // format date
                    $item->date_string = $item->booking->reservation_time_slot->date->format('D, M j');
                
                    // format time
                    $time_start = $item->booking->reservation_time_slot->time;

                    $time_end = clone $item->booking->reservation_time_slot->time;
                    $time_end->add(new DateInterval('PT' . $item->booking->reservation_time_slot->duration . 'M'));

                    $item->time_string = $time_start->format('H:i') . ' - ' . $time_end->format('H:i'); 

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
}
