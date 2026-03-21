<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when the date pathed through form post request is invalid
*/
class FormDataException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}