<?php

namespace App\Models;

use App\Enums\BookingType;
use DateTime;

class StoryBooking implements IBooking {
    public int $booking_id;
    public int $event_id;
    public int $quantity;
    public bool $haarlem_pass;
    public ?string $haarlem_pass_code;
    public ?int $pay_as_you_like;

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

    public function getBookingStartDate() : ?DateTime{
        if(!isset($this->event)) return null;

        return new DateTime($this->event->start_time);
    }
    public function getBookingEndDate() : ?DateTime{
        if(!isset($this->event)) return null;
        
        return new DateTime($this->event->end_time);
    }
    public function getAddressFull() : ?string{
        if(!isset($this->event)) return null;
        
        return $this->event->address_text;
    }
    public function getEventName() : ?string{
        if(!isset($this->event)) return null;

        return "Reservation " . $this->event->name;
    }
    public function getQuantityString() : ?string{
        return 'tickets: ' . $this->quantity;
    }
}