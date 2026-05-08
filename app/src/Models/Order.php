<?php

namespace App\Models;

use App\Enums\OrderStatus;
use DateTime;

class Order{
    public int $order_id;
    public int $user_id;
    public ?DateTime $date;
    public OrderStatus $status;
    public ?int $total_price;
    public ?string $stripe_session;

    public ?array $order_items;

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = $value == null ? null : new DateTime($value);
        }
        else if($name == "status_") {
            $this->status = OrderStatus::from($value);
        }
    }
}