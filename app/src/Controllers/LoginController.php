<?php
namespace App\Controllers;

use App\Services\UserService;
use Exception;

class LoginController
{
    public function index()
    {
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require __DIR__ . '/../Views/login.php';
    }

    public function login()
    {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception("Email and password are required.");
            }

            $userService = new UserService();
            $user = $userService->authenticate($email, $password);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            header("Location: /");
            exit;

        } catch (Exception $e) {
            $_SESSION['login_error'] = $e->getMessage();
            header("Location: /login");
            exit;
        }
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: ");
        exit;
    }
}