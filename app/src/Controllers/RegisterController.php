<?php
namespace App\Controllers;

use App\Services\UserService;
use Exception;

class RegisterController
{
    public function index()
    {
        require __DIR__ . '/../Views/register.php';
    }

    public function register()
    {
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $name = $input['name'] ?? '';
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("Name, email, and password are required.");
            }

            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) { // Verify if email is real
                throw new Exception("You must provide valid email address." . $email);
            }

            $userService = new UserService();
            $userService->registerUser($name, $email, $password);

            echo json_encode(['success' => true, 'message' => 'Registration successful!']);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}