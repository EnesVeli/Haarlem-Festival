<?php

namespace App\Models\Exceptions;

use Exception;
use Throwable;

/**
* Thrown when the cart is empty, when it is expected to not be such (e.g. during removing/modifing cart item). 
*/
class EmptyCartException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }
}