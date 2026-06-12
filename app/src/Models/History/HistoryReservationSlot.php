<?php

namespace App\Models\History;

use DateTime;

class HistoryReservationSlot{
    public int $reservation_id;
    public int $slot_id;
    public DateTime $date;
    public int $booked;

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = $value == null ? null : new DateTime($value);
        }
    }
}