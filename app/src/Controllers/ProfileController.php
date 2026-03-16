<?php
namespace App\Controllers;

use App\Models\Exceptions\IncorrectEmailException;
use App\Services\UserService;

class ProfileController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    private function mustBeLoggedIn(): void //base controllr
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index()
    {
        $this->mustBeLoggedIn();

        $user = $this->userService->getById((int)$_SESSION['user_id']);

        // Optional: show feedback messages (if you later add them in update())
        $success = $_SESSION['profile_success'] ?? null;
        $error   = $_SESSION['profile_error'] ?? null;
        unset($_SESSION['profile_success'], $_SESSION['profile_error']);

        require __DIR__ . '/../Views/manageProfile/profile.php';
    }

    public function update()
    {
        $this->mustBeLoggedIn();

        try {
            $this->userService->updateProfile(
                (int)$_SESSION['user_id'],
                $_POST,
                $_FILES
            );

            // Refresh session values so homepage/navbar updates immediately
            if (isset($_POST['name']) && trim($_POST['name']) !== '') {
                $_SESSION['name'] = trim($_POST['name']);
            }
            if (isset($_POST['email']) && trim($_POST['email']) !== '') {
                $_SESSION['email'] = trim($_POST['email']); 
            }

            $_SESSION['profile_success'] = "Profile updated.";
        } 
        catch (IncorrectEmailException $e) {
            $_SESSION['profile_error'] = "Enter a valid email";
        }
        catch (\Exception $e) {
            $_SESSION['profile_error'] = $e->getMessage();
        }

        header('Location: /profile');
        exit;
    }
}
