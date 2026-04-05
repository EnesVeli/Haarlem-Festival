<?php
namespace App\Controllers;

use App\Interfaces\ICartService;
use App\Services\UserService;
use App\Framework\Session;
use Exception;

class LoginController
{
    private UserService $userService;
    private ICartService $cartService;

    public function __construct(UserService $userService, ICartService $cartService)
    {
        $this->userService = $userService;
        $this->cartService = $cartService;
    }

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
    
            $user = $this->userService->authenticate($email, $password);
            Session::login($user);
            $_SESSION['cart_count'] = $this->cartService->getItemCount((int)$user['user_id']);
    
            header('Location: ' . (Session::isAdmin() ? '/cms' : '/'));
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
