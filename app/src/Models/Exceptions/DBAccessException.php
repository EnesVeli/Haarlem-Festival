<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when unable to access the database, or when an error happens during query execution.
*/
class DBAccessException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}