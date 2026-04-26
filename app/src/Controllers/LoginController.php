<?php
namespace App\Controllers;

use App\Interfaces\ICartService;
use App\Services\UserService;
use App\Framework\Session;
use Exception;

class LoginController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        $error = Session::pop('login_error');
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

            $user = $this->userService->authenticate($email, $password);
            Session::login($user);
            
            if (Session::isAdmin()) {
                header('Location: /cms');
            } elseif (($user['role'] ?? '') === 'employee') {
                header('Location: /employee/scan');
            } else {
                header('Location: /');
            }

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