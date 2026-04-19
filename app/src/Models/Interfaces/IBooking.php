<?php

namespace App\Models;

use App\Enums\BookingType;

interface IBooking {
    public function setBookingId(int $booking_id) : void;
    public function getBookingId() : ?int;
    public function getBookingType() : BookingType;
}