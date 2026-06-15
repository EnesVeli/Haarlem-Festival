<?php

namespace App\Models\History;

use App\Enums\BookingType;
use App\Models\IBooking;
use App\Services\OrderService;
use DateInterval;
use DateTime;

class HistoryBooking implements IBooking{
    public int $booking_id;
    public int $reservation_id;
    public DateTime $date;
    public string $language;
    public int $individual_count;
    public int $family_count;

    public ?HistoryReservationSlot $reservation;
    public ?HistoryTimeSlot $time_slot;

    public function setBookingId(int $booking_id) : void{
        $this->booking_id = $booking_id;
    }
    public function getBookingId() : ?int{
        return isset($this->booking_id) ? $this->booking_id : null;
    }
    public function getBookingType() : BookingType{
        return BookingType::History;
    }

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = $value == null ? null : new DateTime($value);
        }
    }

    public function getBookingStartDate() : ?DateTime{
        return clone $this->date;
    }
    public function getBookingEndDate() : ?DateTime{
        $end = clone $this->date;
        $end->add(new DateInterval('PT' . OrderService::$HISTORY_ROUTE_DURATION . 'M')); 
        return $end;
    }
    public function getAddressFull() : ?string{
        return "Grote Markt 22, 2011 HL Haarlem";
    }
    public function getAddressShort() : ?string{
        return 'St. Bavo Church';
    }
    public function getEventName() : ?string{
        return "Guided Tour";
    }
    public function getQuantityString() : ?string{
        return 'individual: ' . $this->individual_count . "; family: " . $this->family_count;
    }
    public function getCartDescString() : ?string{
        return "individual tickets: " . $this->individual_count . "; family tickets: " . $this->family_count . "; language: " . $this->language;
    }

    public function getEventImagePath() : ?string{
        return '/assets/uploads/History/bavo-church.jpg';
    }
}