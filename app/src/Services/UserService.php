<?php
namespace App\Services;

use App\Enums\UserRole;
use App\Models\Exceptions\AccountNotActiveException;
use App\Models\Exceptions\DBDataFetchException;
use App\Models\Exceptions\DBDataNotFoundException;
use App\Models\Exceptions\EmailAlreadyRegisteredException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\QueryExecutionException;
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

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role = UserRole::Customer;
        $user->active = true;

        if(!$this->userRepository->create($user)) throw new QueryExecutionException('Failed to create new user'); 
    }

    public function authenticate(string $email, string $password) : User
    {
        $user = $this->userRepository->findByEmail($email);

        if($user === null) throw new DBDataFetchException('Failed to get user by eamil.');

        if ($user === null || !password_verify($password, $user->password)) {
            throw new Exception("Invalid email or password.");
        }

        if($user->active === false) throw new AccountNotActiveException('Failed to get user by eamil.');

        return $user;
    }

    public function getById(int $userId) : ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function updateProfile(int $userId, array $data, array $files): void
    {
        $name  = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');

        $this->verification_service->verifyEmail($email);

        $user = $this->getById($userId);
        if($user === null) throw new DBDataNotFoundException('Failed to get user by id.');

        // name
        if ($name !== '' && $name !== $user->name) {
            $this->userRepository->updateName($userId, $name);
        }

        // email 
        if ($email !== '' && $email !== $user->email) {
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

            if ($current === '' || !password_verify($current, $user->password)) {
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