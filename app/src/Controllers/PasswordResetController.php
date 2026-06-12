<?php
namespace App\Controllers;

use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\EmptyPasswordException;
use App\Models\Exceptions\ExpiredKeyException;
use App\Models\Exceptions\InappropriatePasswordLengthException;
use App\Models\Exceptions\IncorrectEmailException;
use App\Models\Exceptions\InvalidKeyException;
use App\Models\Exceptions\PasswordMismatchException;
use App\Services\PasswordResetService;
use Exception;
use Throwable;

class PasswordResetController 
{
    private PasswordResetService $service;

    public function __construct()
    {
        $this->service = PasswordResetService::getInstance();
    }

    public function index(){
        require __DIR__ . '/../Views/password-reset/request.php';
    }

    public function requestPasswordReset(){
        try{
            $this->service->requestPasswordReset($_POST['email']);

            require __DIR__ . '/../Views/password-reset/request-success.php';
        }
        catch(IncorrectEmailException $ex){
            $error_message = 'You must enter a valid email of an existing account for password reset.';
            require __DIR__ . '/../Views/password-reset/request.php';
        }
        catch(DBAccessException $ex){
            $error_message = 'Something went wrong try again later. ' . $ex->getMessage();
            require __DIR__ . '/../Views/password-reset/request.php';
        }
        catch(Throwable $ex){       
            $error_message = 'Something went wrong try again later. ' . $ex->getMessage(); 
            require __DIR__ . '/../Views/password-reset/request.php';
        }
    }

    public function passwordResetVerifyEmail(){
        if($_GET['key'] !== null){
            $key = $_GET['key'];

            require __DIR__ . '/../Views/password-reset/reset-confirm.php';
        }
        else{
            header("Location: /login");
            exit;
        }     
    }

    public function startPasswordReset(){
        try{
            $key = $_POST['key'];
            $email = $_POST['email'];

            $this->service->startPasswordReset($key, $email);

            require __DIR__ . '/../Views/password-reset/reset.php';
            exit;
        }
        catch(IncorrectEmailException $ex){
            $error_message = 'You must enter a valid email of an existing account for password reset.';
            require __DIR__ . '/../Views/password-reset/reset-confirm.php';
        }
        catch(InvalidKeyException $ex){
            $error_message = 'The key is invalid, request a new one.'; 
            require __DIR__ . '/../Views/password-reset/request.php';
        }
        catch(ExpiredKeyException $ex){
            $error_message = 'Your key has expired. Request a new one.'; 
            require __DIR__ . '/../Views/password-reset/request.php';
        }
        catch(Exception $ex){
            $error_message = 'Something went wrong, request a new reset later.'; 
            require __DIR__ . '/../Views/password-reset/request.php';
        } 
    }

    public function resetPassword(){
        try{
            $key = $_POST['key'] ?? null;
            $email = $_POST['email'] ?? null;

            $this->service->resetPassword($_POST['password'] ?? null, $_POST['password_confirm'] ?? null, $email, $key);
            
            require __DIR__ . '/../Views/password-reset/reset-success.php';
            exit;
        }
        catch(EmptyPasswordException $ex){
            $error_message = "The password must not be empty";
            require __DIR__ . '/../Views/password-reset/reset.php';
        }
        catch(InappropriatePasswordLengthException $ex){
            $error_message = "Your password must be at least 8 and maximum 255 characters.";
            require __DIR__ . '/../Views/password-reset/reset.php';
        }
        catch(PasswordMismatchException $ex){
            $error_message = "The passwords do not match.";
            require __DIR__ . '/../Views/password-reset/reset.php';
        }
        catch(ExpiredKeyException $ex){
            $error_message = "Your key is expired, request a new one.";
            require __DIR__ . '/../Views/password-reset/request.php';
        }
        catch(Exception $ex){
            $error_message = "Something went wrong, request a new reset later.";
            require __DIR__ . '/../Views/password-reset/request.php';
        }
    }
}