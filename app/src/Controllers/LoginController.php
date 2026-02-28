<?php
namespace App\Controllers;

use App\Services\UserService;
use App\Framework\Session;
use Exception;

class LoginController
{
    public function index()
    {
        $error = Session::flash('login_error');
        require __DIR__ . '/../Views/login.php';
    }

    public function login()
    {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                throw new Exception("Email and password are required.");
            }

            $userService = new UserService();
            $user = $userService->authenticate($email, $password);

            Session::login($user);

            header("Location: /");
            exit;

        } catch (Exception $e) {
            Session::set('login_error', $e->getMessage());
            header("Location: /login");
            exit;
        }
    }
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /');
        exit;
    }
}