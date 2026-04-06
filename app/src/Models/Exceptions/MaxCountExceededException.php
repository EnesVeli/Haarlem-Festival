<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when maximum count is exceeded
*/
class MaxCountExceededException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}