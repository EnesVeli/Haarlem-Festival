<?php

namespace App\Services;

use App\Config;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetTokenRepository;
use DateException;
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

    public function requestPasswordReset(string $email) : ?array
    {
        $user = $this->user_repository->findByEmail($email);

        if($user == null || $user['user_id'] == null){
            return null;
        }
        else{
            $token = $this->password_reset_token_repository->getTokenByUserId($user['user_id']);

            $key = $this->config->generateKey();

            if($token == null){
                if(!$this->password_reset_token_repository->createNewToken($user['user_id'], password_hash($key, PASSWORD_DEFAULT))){
                    throw new Exception("Cannot create password reset token!");
                }         
            }
            else{
                if(!$this->password_reset_token_repository->updateToken($token['token_id'], password_hash($key, PASSWORD_DEFAULT))){
                    throw new Exception("Cannot update password reset token!");
                } 
            }

            $array = array(
                "key" => $key,
                "name" => $user['name']
            );

            return $array;
        }
    }  

    public function verifyKey(string $key) : bool {
        //echo $key . ' -> ' . password_hash($key, PASSWORD_DEFAULT);

        $token = $this->password_reset_token_repository->getTokenByKey(password_hash($key, PASSWORD_DEFAULT));

        return $token !== null;
    }

    public function getEmailResetToken(string $key, string $email) : ?array
    {
        $user = $this->user_repository->findByEmail($email);

        if($user == null || $user['user_id'] == null){
            throw new DateException('You have entered incorect email.');
        }

        $token = $this->password_reset_token_repository->getTokenByUserId($user['user_id']);

        if($token == null || !password_verify($key, $token['key'])) 
        {
            throw new Exception('Invalid key.');
        }

        if((strtotime($token['created_at']) - time()) / 60 > Config::RESET_LINK_TIMEOUT) 
        {
            throw new Exception('Your password reset link has expired. Reenter your email to get a new one.');
        }

        $_SESSION['key_time'] = time();

        return $token;
    }

    public function resetPassword(string $password, string $password_confirm, array $token){
        if($_SESSION['key_time'] == null || (strtotime($_SESSION['key_time']) - time()) / 60 > Config::RESET_LINK_SET_TIMEOUT){
            $this->password_reset_token_repository->deleteToken($token['token_id']);
            throw new Exception('Your password reset link has expired. Reenter your email to get a new one');
        }

        if(!$this->user_repository->changePassword($token['user_id'], password_hash($password, PASSWORD_DEFAULT))){
            throw new Exception('ERROR!!!');
        }

        $this->password_reset_token_repository->deleteToken($token['token_id']);
    }

    public function sendResetEmail(string $email, string $name, string $key){
        $mail_service = new MailService(); 
        $mail_service->sendPasswordReset($email, $name, $key); 
    }
}