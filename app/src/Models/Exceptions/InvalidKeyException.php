<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when the key is invalid
*/
class InvalidKeyException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}