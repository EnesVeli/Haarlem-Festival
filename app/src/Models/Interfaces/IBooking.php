<?php

namespace App\Models;

use App\Enums\BookingType;
use DateTime;

interface IBooking {
    public function setBookingId(int $booking_id) : void;
    public function getBookingId() : ?int;
    public function getBookingType() : BookingType;
    public function getBookingStartDate() : ?DateTime;
    public function getBookingEndDate() : ?DateTime;
    public function getAddressFull() : ?string;
    public function getEventName() : ?string;
    public function getQuantityString() : ?string;
}