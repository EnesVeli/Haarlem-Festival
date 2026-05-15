<?php

namespace App\Models;

use App\Enums\BookingType;

class OrderItem{
    public int $item_id;
    public int $order_id;
    public int $booking_id;
    public BookingType $booking_type;
    public int $price;

    public ?IBooking $booking;

    public function __set($name, $value) {
        if($name == "booking_type_") {
            $this->booking_type = BookingType::from($value);
        }
    }

    public function getBookingTypeString() : string {
        if(!isset($this->booking_type)) return '';
    
        switch($this->booking_type){
            case BookingType::History:
                return 'History';
            case BookingType::Stories:
                return 'Stories';
            case BookingType::Yummy:
                return 'Yummy';
            case BookingType::Jazz:
                return 'Jazz';
            default:
                return '';
        }
    }
}