<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
 * Thrown when geting data from db resulted in geting empty response.
 */
class DBDataNotFoundException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}
