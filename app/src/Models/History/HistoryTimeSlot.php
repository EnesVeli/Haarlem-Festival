<?php

namespace App\Models\History;

use DateTime;

class HistoryTimeSlot{
    public int $slot_id;
    public DateTime $time;
    public int $capacity;

    function __set($name, $value) {
        if($name == "time_") {
            $this->time = $value == null ? null : new DateTime($value);
        }
    }
}