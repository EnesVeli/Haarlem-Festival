<?php

namespace App\Models;

use App\Enums\BookingType;
use DateInterval;
use DateTime;

class YummyBooking implements IBooking {
    public int $booking_id;
    public int $reservation_id;
    public DateTime $date;
    public int $adult_number;
    public int $child_number;
    public string $comment;

    public ?RestaurantTimeSlot $reservation_time_slot;
    public ?Restaurant $restaurant;

    public function setBookingId(int $booking_id) : void{
        $this->booking_id = $booking_id;
    }
    public function getBookingId() : ?int{
        return isset($this->booking_id) ? $this->booking_id : null;
    }
    public function getBookingType() : BookingType{
        return BookingType::Yummy;
    }

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = new DateTime($value);
        }
    }

    public function getBookingStartDate() : ?DateTime{
        return clone $this->date;
    }
    public function getBookingEndDate() : ?DateTime{
        if(!isset($this->reservation_time_slot)) return null;

        $end = clone $this->date;
        $end->add(new DateInterval('PT' . $this->reservation_time_slot->duration . 'M')); 
        return $end;
    }
    public function getAddressFull() : ?string{
        if(!isset($this->restaurant)) return null;

        return $this->restaurant->address_text;
    }
    public function getEventName() : ?string{
        if(!isset($this->restaurant)) return null;

        return $this->restaurant->name . " Booking";
    }
    public function getQuantityString() : ?string{
        return 'adults: ' . $this->adult_number . '; children: ' . $this->child_number;
    }
}