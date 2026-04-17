<?php

namespace App\Models;

use App\Enums\BookingType;

interface IBooking {
    public function getBookingId() : int;
    public function getBookingType() : BookingType;
}