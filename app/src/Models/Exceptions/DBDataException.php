<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when the expected data, and data in db do not match. Can be a result of db corruption.
*/
class DBDataException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}