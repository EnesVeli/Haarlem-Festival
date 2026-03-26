<?php
namespace App\Controllers;

use App\Models\Exceptions\EmailAlreadyRegesteredException;
use App\Models\Exceptions\EmptyFieldException;
use App\Services\UserService;
use App\Models\Exceptions\EmptyPasswordException;
use App\Models\Exceptions\InappropriatePasswordLengthException;
use App\Models\Exceptions\PasswordMismatchException;
use App\Models\Exceptions\IncorrectEmailException;
use App\Config;
use Exception;

class RegisterController
{
    private UserService $user_service;

    public function __construct(){
        $this->user_service = new UserService();
    }

    public function index()
    {
        require __DIR__ . '/../Views/register.php';
    }

    public function register()
    {
        try {
            $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

            if (empty($recaptchaResponse)) {
                $error_message = "Please check the 'I am not a robot' box.";
                require __DIR__ . '/../Views/register.php';
                exit;
            }

            // Call Google API to verify the token
            $secretKey = Config::RECAPTCHA_SECRET_KEY;
            $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}";
            $verifyResponse = file_get_contents($verifyUrl);
            $responseData = json_decode($verifyResponse);

            if (!$responseData->success) {
                $error_message = "CAPTCHA verification failed. Please try again.";
                require __DIR__ . '/../Views/register.php';
                exit;
            }

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password-confirm'] ?? '';

            $this->user_service->registerUser($name, $email, $password, $password_confirm);

            require __DIR__ . '/../Views/register-success.php';
            exit; 
        } 
        catch(EmptyFieldException $ex){
            $error_message = "Name, email and password should not be empty";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
        catch(EmailAlreadyRegesteredException $ex){
            $error_message = "This email is already registered.";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
        catch(EmptyPasswordException $ex){
            $error_message = "The password must not be empty";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
        catch(InappropriatePasswordLengthException $ex){
            $error_message = "Your password must be at least 8 and maximum 255 characters.";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
        catch(PasswordMismatchException $ex){
            $error_message = "The passwords do not match.";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
        catch(IncorrectEmailException $ex){
            $error_message = "Enter a valid email address.";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
        catch(Exception $ex){
            $error_message = "Something went wrong, try again later.";
            require __DIR__ . '/../Views/register.php';
            exit;
        }
    }
}