<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
 * Throw when expected post argument is empty.
 */
class EmptyPostException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}