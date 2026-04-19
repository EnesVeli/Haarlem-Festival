<?php

namespace App\Models;

use App\Enums\BookingType;

include '/app/src/Models/Interfaces/IBooking.php';

class StoryBooking implements IBooking {
    public int $booking_id;
    public int $event_id;
    public int $quantity;
    public bool $haarlem_pass;
    public ?string $haarlem_pass_code;

    public ?StoryEvent $event;

    public function setBookingId(int $booking_id) : void{
        $this->booking_id = $booking_id;
    }
    public function getBookingId() : ?int{
        return isset($this->booking_id) ? $this->booking_id : null;
    }
    public function getBookingType() : BookingType{
        return BookingType::Stories;
    }
}