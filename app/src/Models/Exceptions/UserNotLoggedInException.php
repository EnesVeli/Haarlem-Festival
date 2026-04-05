<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when user is not logged in when expected.
*/
class UserNotLoggedInException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}