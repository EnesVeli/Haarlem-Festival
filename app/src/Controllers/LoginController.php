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

    if (ini_get('session.use_cookies')) {
        // Get the cookie settings PHP is using (path, domain, secure, httponly)
        $params = session_get_cookie_params();

        // Overwrite the cookie with an expired time => browser deletes it
        setcookie(
            session_name(),      // cookie name (usually PHPSESSID)
            '',                  // empty value
            time() - 42000,      // old timestamp => expired cookie
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // 3) Destroy the session on the server (removes the session file/data)
    session_destroy();

    // 4) UX: after logout, send the visitor to the home page (not a blank page)
    //    This is for normal browser clicks (GET /logout).
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
        header('Location: /');
        exit;
    }

    // 5) If logout is called via API/AJAX (POST /logout), return JSON instead.
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logged out']);
}
}