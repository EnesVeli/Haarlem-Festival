<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
 * Thrown when post arguments do not match. Usually occurs when post arguments are set incorrectly, or when they were modified by user.
 */
class PostMismatchException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}