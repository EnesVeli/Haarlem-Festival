<?php
namespace App\Services;

use App\Enums\UserRole;
use App\Models\Exceptions\EmailAlreadyRegisteredException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;

class UserService
{
    private static ?UserService $_instance = null;

    public static function getInstance() : UserService {
        if(self::$_instance === null) self::$_instance = new UserService(UserRepository::getInstance(), VerificationService::getInstance());

        return self::$_instance;
    }

    private UserRepository $userRepository;
    private VerificationService $verification_service;

    private function __construct(UserRepository $userRepository, VerificationService $verification_service)
    {
        $this->userRepository = $userRepository;
        $this->verification_service = $verification_service;
    }

    public function registerUser(string $name, string $email, string $password, string $password_confirm): void
    {
        if(empty($name)) throw new EmptyFieldException();

        $this->verification_service->verifyEmail($email);
        $this->verification_service->verifyPassword($password, $password_confirm);

        if ($this->userRepository->findByEmail($email)) throw new EmailAlreadyRegisteredException();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->userRepository->create($name, $email, $hashedPassword, 'customer');
    }

    public function authenticate(string $email, string $password): User
    {
        $row = $this->userRepository->findByEmail($email);

        if (!$row || !password_verify($password, $row['password'])) {
            throw new Exception("Invalid email or password.");
        }

        $user = new User();
        $user->user_id = (int)$row['user_id'];
        $user->email   = $row['email'];
        $user->name    = $row['name'];

        $raw = $row['role'] ?? null;
        if (is_numeric($raw)) {
            $user->role = UserRole::from((int)$raw);
        } else {
            $map = ['customer' => UserRole::Customer, 'admin' => UserRole::Admin, 'employee' => UserRole::Employee];
            $user->role = $map[strtolower((string)$raw)] ?? UserRole::Customer;
        }

        return $user;
    }

    public function getById(int $userId): array
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) throw new Exception("User not found.");
        return $user;
    }

    public function updateProfile(int $userId, array $data, array $files): void
    {
        $name  = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');

        $this->verification_service->verifyEmail($email);

        $user = $this->getById($userId);

        // name
        if ($name !== '' && $name !== $user['name']) {
            $this->userRepository->updateName($userId, $name);
        }

        // email 
        if ($email !== '' && $email !== $user['email']) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }
            if ($this->userRepository->findByEmail($email)) {
                throw new Exception("This email is already registered.");
            }
            $this->userRepository->updateEmail($userId, $email);
        }

        // password change (require current password)
        $newPass = trim($data['new_password'] ?? '');
        if ($newPass !== '') {
            $current = trim($data['current_password'] ?? '');

            if ($current === '' || !password_verify($current, $user['password'])) {
                throw new Exception("Current password is incorrect.");
            }
            if (strlen($newPass) < 8) {
                throw new Exception("Password must be at least 8 characters long.");
            }
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $this->userRepository->updatePassword($userId, $hash);
        }
        // profile picture upload (store path in profile_picture_url)
        if (!empty($files['profile_picture']['name'])) {
            $tmp  = $files['profile_picture']['tmp_name'];
            $err  = $files['profile_picture']['error'];

            if ($err !== UPLOAD_ERR_OK) throw new Exception("Upload failed.");

            $ext = strtolower(pathinfo($files['profile_picture']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $allowed, true)) throw new Exception("Only jpg, png, webp allowed."); 

            $dir = __DIR__ . '/../../public/assets/uploads';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $filename = "user_{$userId}_" . time() . "." . $ext;
            $dest = $dir . "/" . $filename;

            if (!move_uploaded_file($tmp, $dest)) {
                throw new Exception("Could not save uploaded file.");
            }

            // URL path for browser
            $url = "/assets/uploads/" . $filename;
            $this->userRepository->updateProfilePictureUrl($userId, $url);
        }
    }

}