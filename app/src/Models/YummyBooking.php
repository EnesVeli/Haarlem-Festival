<?php

namespace App\Models;

use App\Enums\BookingType;
use DateTime;

include '/app/src/Models/Interfaces/IBooking.php';

class YummyBooking implements IBooking {
    public int $booking_id;
    public int $reservation_id;
    public DateTime $date;
    public int $adult_number;
    public int $child_number;
    public string $comment;

    public ?RestaurantTimeSlot $reservation_time_slot;
    public ?Restaurant $restaurant;

    public function getBookingId() : int{
        return $this->booking_id;
    }
    public function getBookingType() : BookingType{
        return BookingType::Yummy;
    }

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = new DateTime($value);
        }
    }
}