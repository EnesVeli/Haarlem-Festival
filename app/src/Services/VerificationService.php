<?php

namespace App\Services;

use App\Models\Exceptions\EmptyPasswordException;
use App\Models\Exceptions\InappropriatePasswordLengthException;
use App\Models\Exceptions\PasswordMismatchException;
use App\Models\Exceptions\IncorrectEmailException;

class VerificationService{
    private static ?VerificationService $_instance = null;

    private function __construct(){}

    public static function getInstance() : VerificationService {
        if(self::$_instance === null) self::$_instance = new VerificationService();

        return self::$_instance;
    }
    /**
    * Throws either EmptyPasswordException, InappropriatePasswordLengthException or PasswordMismatchException, if password is not appropriate.
    * If password is appropriate, then does not throw any exception.
    */
    public function verifyPassword(?string $password, ?string $password_confirm) : void {
        if($password == null || empty(trim($password))) throw new EmptyPasswordException();

        if(strlen($password) < 8 || strlen($password) > 255) throw new InappropriatePasswordLengthException();  

        if($password !== $password_confirm) throw new PasswordMismatchException();
    }

    /**
    * Throws IncorrectEmailException, if email is not appropriate.
    * If email is appropriate, then does not throw any exception.
    */
    public function verifyEmail(?string $email){
        if($email == null || empty(trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) throw new IncorrectEmailException();    
    }
}