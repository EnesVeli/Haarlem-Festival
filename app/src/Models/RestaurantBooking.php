<?php

namespace App\Models;

use DateTime;

class RestaurantBooking
{
    public int $booking_id;
    public int $reservation_id;
    public int $user_id;
    public DateTime $date;
    public int $adult_number;
    public int $child_number;
    public ?string $comment;

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = new DateTime($value);
        }
    }
}