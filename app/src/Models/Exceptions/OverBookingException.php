<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown during booking a reservation, if capacity is already at maximum. 
*/
class OverBookingException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}