<?php

namespace App\Models;

use App\Enums\BookingType;
use DateTime;

class OrderItem{
    public int $item_id;
    public int $order_id;
    public int $booking_id;
    public BookingType $booking_type;
    public int $price;

    public ?IBooking $booking;

    function __set($name, $value) {
        if($name == "booking_type_") {
            $this->booking_type = BookingType::from($value);
        }
    }
}