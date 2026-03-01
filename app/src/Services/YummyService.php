<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\PasswordResetTokenRepository;
use Exception;

class YummyService
{
    private PasswordResetTokenRepository $password_reset_token_repository;
    private UserRepository $user_repository;

    public function __construct()
    {
        $this->user_repository = new UserRepository();
        $this->password_reset_token_repository = new PasswordResetTokenRepository();
    } 
}