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

    public function getById(int $userId): array
{
    $user = $this->userRepository->findById($userId);
    if (!$user) throw new Exception("User not found.");
    return $user;
}

public function updateProfile(int $userId, array $data, array $files): void
{
    $user = $this->getById($userId);

    $name  = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');

    // name
    if ($name !== '' && $name !== $user['name']) {
        $this->userRepository->updateName($userId, $name);
    }

    // email (for now: direct update; later: confirmation flow)
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
// user model
//validation method
//image upload method
    // profile picture upload (store path in profile_picture_url)
    if (!empty($files['profile_picture']['name'])) {
        $tmp  = $files['profile_picture']['tmp_name'];
        $err  = $files['profile_picture']['error'];

        if ($err !== UPLOAD_ERR_OK) throw new Exception("Upload failed.");

        $ext = strtolower(pathinfo($files['profile_picture']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed, true)) throw new Exception("Only jpg, png, webp allowed."); //sescure image upload handling (so no maltious code) research

        $dir = __DIR__ . '/../../public/assets/uploads';
        if (!is_dir($dir)) mkdir($dir, 0777, true); //too permissive public

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