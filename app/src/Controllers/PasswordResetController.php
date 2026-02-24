<?php
namespace App\Controllers;

use App\Services\MailService;
use App\Services\PasswordResetService;
use Exception;
use Throwable;

class PasswordResetController 
{
    private PasswordResetService $service;

    public function __construct()
    {
        $this->service = new PasswordResetService();
    }

    public function index(){
        require __DIR__ . '/../Views/password-reset/request.php';
    }

    public function requestPaawordReset(){
        $email = $_POST['email'] ?? '';

        if(empty(trim($email))){
            $error = 'You must enter a valid email for password reset.';
            require __DIR__ . '/../Views/password-reset/request.php';
            exit;
        }

        try{
            $request = $this->service->requestPasswordReset($email);

            if($request == null || $request['key'] == null){
                $error = 'Account with this email does not exists. Check if you have entered a correct email.';
                require __DIR__ . '/../Views/password-reset/request.php';
            }
            else{
                //$_SESSION['key'] = $request['key']; // Debug only!

                //$this->service->sendResetEmail($email, $request['name'], $request['key']); 

                require __DIR__ . '/../Views/password-reset/request-success.php';
            }          
        }
        catch(Throwable $ex){        
            require __DIR__ . '/../Views/password-reset/request-fail.php';

            echo $ex->getMessage(); // Debug only!
        }
    }

    public function startPasswordReset(){
        unset($_SESSION['key']);

        if($_GET['key'] !== null && $this->service->verifyKey($_GET['key'])){
            $_SESSION['key'] = $_GET['key'];

            require __DIR__ . '/../Views/password-reset/reset-confirm.php';
        }
        else{
            header("Location: /login");
            exit;
        }     
    }

    public function createNewPassword(){
        if($_SESSION['key'] == null){
            header("Location: /login");
            exit;
        }

        $email = $_POST['email'];

        if($email == null || empty(trim($email))){
            $error = 'Please enter your email';
            require __DIR__ . '/../Views/password-reset/reset-confirm.php';
            exit;
        }

        try{
            $_SESSION['token'] = $this->service->getEmailResetToken($_SESSION['key'], $email);
            require __DIR__ . '/../Views/password-reset/reset.php';
        }
        catch(Exception $ex){
            $error = $ex->getMessage();
            require __DIR__ . '/../Views/password-reset/request.php';
        } 
    }

    public function resetPassword(){
        $password = $_POST['password'];
        $password_confirm = $_POST['password-confirm'];

        if($password == null || empty(trim($password))){
            $error = 'Password must not be empty.';
            require __DIR__ . '/../Views/password-reset/reset.php';
            exit;
        }

        if(strlen($password) < 8){
            $error = 'Password must be at least 8 characters long.';
            require __DIR__ . '/../Views/password-reset/reset.php';
            exit;
        }

        if($password !== $password_confirm){
            $error = 'Password and password confirmation does not match.';
            require __DIR__ . '/../Views/password-reset/reset.php';
            exit;
        }

        try{
            $this->service->resetPassword($password, $password_confirm, $_SESSION['token']);
            require __DIR__ . '/../Views/password-reset/reset-success.php';
        }
        catch(Exception $ex){
            $error = $ex->getMessage();
            require __DIR__ . '/../Views/password-reset/reset.php';
        }
    }
}