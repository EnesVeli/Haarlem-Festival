<?php
namespace App\Controllers;

use App\Services\UserService;
use App\Framework\Session;
use Exception;

class LoginController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = UserService::getInstance();
    }

    public function index(): void
    {
        $error = Session::popTempError();

        $this->render('login', [
            'error'     => $error,
            'pageTitle' => 'Login — The Festival Haarlem',
            'mainClass' => 'login-main',
            'pageCSS' => 'login.css',
            'user'      => Session::user(),
        ]);
    }

    public function login(): void
    {
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            Session::setTempError('Email and password are required.');
            header('Location: /login');
            exit;
        }

        try {
            $user = $this->userService->authenticate($email, $password);
            Session::login($user);

            if (Session::isAdmin()) {
                header('Location: /cms');
            } elseif (Session::isEmployee()) {
                header('Location: /employee/scan');
            } else {
                header('Location: /');
            }

            exit;

        } catch (Exception $e) {
            Session::setTempError('Invalid email or password.');
            header('Location: /login');
            exit;
        }
    }

    public function logout(): void
    {
        Session::logout();
        header('Location: /');
        exit;
    }
}