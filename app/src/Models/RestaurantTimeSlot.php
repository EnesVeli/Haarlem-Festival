<?php

namespace App\Models;

use DateTime;

class RestaurantTimeSlot{
    public int $reservation_id;
    public int $slot_id;
    public int $restaurant_id;
    public DateTime $date;
    public DateTime $time;
    public int $capacity;
    public int $booked;
    public int $duration;

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = new DateTime($value);
        }
        else if($name == "time_"){
            $this->time = new DateTime($value);
        }
    }
}