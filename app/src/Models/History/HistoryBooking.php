<?php

namespace App\Models\History;

use App\Enums\BookingType;
use App\Models\IBooking;
use DateTime;

include '/app/src/Models/Interfaces/IBooking.php';

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
}