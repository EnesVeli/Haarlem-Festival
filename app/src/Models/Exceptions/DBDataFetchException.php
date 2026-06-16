<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
 * Thrown when geting data from db fail or data was null unexpectedly.
 */
class DBDataFetchException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}
