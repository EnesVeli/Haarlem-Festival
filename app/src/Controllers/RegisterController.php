<?php
namespace App\Controllers;

use App\Models\Exceptions\EmailAlreadyRegisteredException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\EmptyPasswordException;
use App\Models\Exceptions\InappropriatePasswordLengthException;
use App\Models\Exceptions\PasswordMismatchException;
use App\Models\Exceptions\IncorrectEmailException;
use App\Services\CaptchaService;
use App\Services\UserService;
use Exception;

/**
 * Handles registration page and form submission.
 */
class RegisterController extends BaseController
{
    private UserService $userService;
    private CaptchaService $captchaService;

    /**
     * @param UserService $userService Registration service
     * @param CaptchaService $captchaService Captcha verification service
     */
    public function __construct(UserService $userService, CaptchaService $captchaService)
    {
        $this->userService = $userService;
        $this->captchaService = $captchaService;
    }

    /**
     * Renders registration form.
     */
    public function index(): void
    {
        $this->render('register', [
            'csrfToken' => $this->ensureCsrfToken(),
        ]);
    }

    /**
     * Handles registration submit.
     */
    public function register(): void
    {
        try {
            if (!$this->captchaService->isValidRegistrationCaptcha($_POST['g-recaptcha-response'] ?? '')) {
                $error_message = "Please check the 'I am not a robot' box.";
                $this->render('register', [
                    'error_message' => $error_message,
                    'csrfToken'     => $this->ensureCsrfToken(),
                ]);
                return;
            }

            $name = htmlspecialchars(trim((string) ($_POST['name'] ?? '')));
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password-confirm'] ?? '';

            $this->userService->registerUser($name, $email, $password, $password_confirm);

            $this->render('register-success');
            return;
        } 
        catch(EmptyFieldException $ex){
            $error_message = "Name, email and password should not be empty";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
            return;
        }
        catch(EmailAlreadyRegisteredException $ex){
            $error_message = "This email is already registered.";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
            return;
        }
        catch(EmptyPasswordException $ex){
            $error_message = "The password must not be empty";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
            return;
        }
        catch(InappropriatePasswordLengthException $ex){
            $error_message = "Your password must be at least 8 and maximum 255 characters.";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
            return;
        }
        catch(PasswordMismatchException $ex){
            $error_message = "The passwords do not match.";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
            return;
        }
        catch(IncorrectEmailException $ex){
            $error_message = "Enter a valid email address.";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
            return;
        }
        catch(Exception $ex){
            $error_message = "Something went wrong, try again later.";
            $this->render('register', [
                'error_message' => $error_message,
                'csrfToken'     => $this->ensureCsrfToken(),
            ]);
        }
    }
}
