<?php

namespace App\Services;

use App\Config;
use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\ExpiredKeyException;
use App\Models\Exceptions\IncorrectEmailException;
use App\Models\Exceptions\InvalidKeyException;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetTokenRepository;
use Exception;

class PasswordResetService
{
    private PasswordResetTokenRepository $password_reset_token_repository;
    private UserRepository $user_repository;
    private VerificationService $verify_service;

    private Config $config;

    public function __construct()
    {
        $this->user_repository = new UserRepository();
        $this->password_reset_token_repository = new PasswordResetTokenRepository();
        $this->config = new Config();
        $this->verify_service = new VerificationService();
    }

    public function requestPasswordReset(?string $email)
    {
        $this->verify_service->verifyEmail($email);

        $user = $this->user_repository->findByEmail($email);

        if($user == null)throw new IncorrectEmailException('Unable to find user by email!');
        
        $token = $this->password_reset_token_repository->getTokenByUserId($user['user_id']);

        $key = $this->config->generateKey();

        if($token == null){
            if(!$this->password_reset_token_repository->createNewToken($user['user_id'], password_hash($key, PASSWORD_DEFAULT))){
                throw new DBAccessException("Cannot create a password reset token!");
            }         
        }
        else{
            if(!$this->password_reset_token_repository->updateToken($token->token_id, password_hash($key, PASSWORD_DEFAULT))){
                throw new DBAccessException("Cannot update a password reset token!");
            } 
        }

        $mail_service = new MailService();
        $mail_service->sendPasswordReset($email, $user['name'], $key);    
    }  

    public function startPasswordReset(?string $key, ?string $email)
    {
        $this->verify_service->verifyEmail($email);
        
        if($key == null) throw new InvalidKeyException();

        $user = $this->user_repository->findByEmail($email);
        if($user == null) throw new IncorrectEmailException('Unable to find user by email!');   

        $token = $this->password_reset_token_repository->getTokenByUserId($user['user_id']);
        if($token == null || !password_verify($key, $token->key)) throw new InvalidKeyException();

        if((time() - $token->creation_date->getTimestamp()) / 60 > Config::RESET_LINK_TIMEOUT) throw new ExpiredKeyException();      

        $this->password_reset_token_repository->setActivationTimeAsNow($token->token_id);
    }

    public function resetPassword(?string $password, ?string $password_confirm, string $email, string $key){
        $this->verify_service->verifyPassword($password, $password_confirm);

        $user = $this->user_repository->findByEmail($email);
        if($user == null) throw new IncorrectEmailException('Unable to find user by email!');   

        $token = $this->password_reset_token_repository->getTokenByUserId($user['user_id']);
        if($token == null || !password_verify($key, $token->key)) throw new InvalidKeyException();

        if($token->activation_date == null || (time() - $token->activation_date->getTimestamp()) / 60 > Config::RESET_LINK_SET_TIMEOUT){
            $this->password_reset_token_repository->deleteToken($token->token_id);
            throw new ExpiredKeyException('Your password reset key has expired.');
        }

        if(!$this->user_repository->changePassword($token->user_id, password_hash($password, PASSWORD_DEFAULT))){
            throw new DBAccessException('Unable to change password');
        }

        $this->password_reset_token_repository->deleteToken($token->token_id);
    }
}