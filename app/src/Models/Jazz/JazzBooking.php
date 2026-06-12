<?php

namespace App\Models\Jazz;

use App\Enums\BookingType;
use App\Models\IBooking;
use DateTime;

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

    public function getBookingStartDate() : ?DateTime{
        if(!isset($this->performer)) return null;

        return new DateTime($this->performer->date->format('Y-m-d') . ' ' . $this->performer->start_time->format('H:i:s'));
    }
    public function getBookingEndDate() : ?DateTime{
        if(!isset($this->performer)) return null;

        return new DateTime($this->performer->date->format('Y-m-d') . ' ' . $this->performer->end_time->format('H:i:s'));
    }
    public function getAddressFull() : ?string{
        if(!isset($this->performer)) return null;

        return $this->performer->venue_address;
    }
    public function getAddressShort() : ?string{
        if(!isset($this->performer)) return null;

        return $this->performer->venue_name;
    }
    public function getEventName() : ?string{
        if(!isset($this->performer)) return null;

        return "Tickets " . $this->performer->name;
    }
    public function getQuantityString() : ?string{
        return 'tickets: ' . $this->amount;
    }

    public function getCartDescString() : ?string{
        return $this->getQuantityString();
    }

    public function getEventImagePath() : ?string{
        if(!isset($this->performer)) return null;

        return $this->performer->image_path;
    }
}