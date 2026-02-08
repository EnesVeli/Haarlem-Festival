<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function registerUser(string $name, string $email, string $password): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        if (empty(trim($name))) {
            throw new Exception("Name is required.");
        }

        if ($this->userRepository->findByEmail($email)) {
            throw new Exception("This email is already registered.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->userRepository->create($name, $email, $hashedPassword, 'customer');
    }

    public function authenticate(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password.");
        }

        return [
            'user_id' => $user['user_id'],
            'email'   => $user['email'],
            'name'    => $user['name'],
            'role'    => $user['role'],
        ];
    }
}