<?php

namespace App\Services;

use App\Config;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetTokenRepository;
use Exception;

class PasswordResetService
{
    private PasswordResetTokenRepository $password_reset_token_repository;
    private UserRepository $user_repository;

    private Config $config;

    public function __construct()
    {
        $this->user_repository = new UserRepository();
        $this->password_reset_token_repository = new PasswordResetTokenRepository();
        $this->config = new Config();
    }

    public function requestPasswordReset(string $email) : ?string
    {
        $user = $this->user_repository->findByEmail($email);

        if($user == null || $user['user_id'] == null){
            return null;
            //throw new Exception("Account with this email does not exists. Checlk if you entered a correct email.");
        }
        else{
            $token = $this->password_reset_token_repository->getTokenByUserId($user['user_id']);

            $key = $this->config->generateKey();

            if($token == null){
                if(!$this->password_reset_token_repository->createNewToken($user['user_id'], $key)){
                    throw new Exception("Cannot create password reset token!");
                }         
            }
            else{
                if(!$this->password_reset_token_repository->updateToken($token['token_id'], $key)){
                    throw new Exception("Cannot update password reset token!");
                } 
            }

            return $key;
        }
    }  

    public function verifyKeyAndGetToken(string $key, string $email) : ?array
    {
        $token = $this->password_reset_token_repository->getTokenByKey($key);

        echo $email . ' ; ' . $token['user_id'];

        if($token == null || (strtotime($token['created_at']) - time()) / 60 > 1) 
        {
            throw new Exception('Your password reset link has expired. Reenter your email to get a new one.');
        }

        $token_user = $this->user_repository->findByUserId($token['user_id']);
        $token_email = $token_user['email'];

        echo $email . ' ; ' . $token_email;

        if($email == null || $email !== $token_email){
            $this->password_reset_token_repository->deleteToken($token['token_id']);
            throw new Exception('You have entered incorect email. Request a new password reset.');
        }

        $_SESSION['key_time'] = time();

        return $token;
    }

    public function resetPassword(string $password, string $password_confirm, array $token){
        if($_SESSION['key_time'] == null || (strtotime($_SESSION['key_time']) - time()) / 60 > 1){
            $this->password_reset_token_repository->deleteToken($token['token_id']);
            throw new Exception('Your password reset link has expired. Reenter your email to get a new one');
        }

        if(!$this->user_repository->changePassword($token['user_id'], password_hash($password, PASSWORD_DEFAULT))){
            throw new Exception('ERROR!!!');
        }
    }
}