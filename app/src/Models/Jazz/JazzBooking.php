<?php

namespace App\Models\Jazz;

use App\Enums\BookingType;
use App\Models\IBooking;

include '/app/src/Models/Interfaces/IBooking.php';

class JazzBooking implements IBooking {
    public int $booking_id;
    public int $performer_id;
    public int $amount;

    public ?JazzPerformer $performer;

    public function setBookingId(int $booking_id) : void{
        $this->booking_id = $booking_id;
    }
    public function getBookingId() : ?int{
        return isset($this->booking_id) ? $this->booking_id : null;
    }
    public function getBookingType() : BookingType{
        return BookingType::Jazz;
    }
}