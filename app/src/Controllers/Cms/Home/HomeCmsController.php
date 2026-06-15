<?php

namespace App\Controllers\Cms\Home;

use App\Services\HomeService;
use App\ViewModels\HomeEditViewModel;
use Exception;

class HomeCmsController
{
    private const MAX_IMAGE_SIZE_BYTES = 5_242_880; // 5 MB
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = HomeService::getInstance();
        $this->requireAdmin();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }

    public function index(): void
    {
        // Ensure CSRF token is generated
        $csrfToken = $this->getCsrfToken();

        try {
            $viewModel = new HomeEditViewModel(
                $this->homeService->getHomeContent(),
                $this->homeService->getAllHomeEvents()
            );
        } catch (Exception $exception) {
            $_SESSION['flash_error'] = 'Something went wrong. Please try again later.';
            $viewModel = new HomeEditViewModel([], []);
        }

        require __DIR__ . '/../../../Views/cms/home/home.php';
    }

    public function saveContent(): void
    {
        try {
            $this->validateCsrfToken();
            $this->guardUploadSize('/cms/home');

            $allowedKeys = [
                'hero_title', 'hero_subtitle',
                'hero_description', 'program_title', 'program_description',
            ];

            $data = [];
            foreach ($allowedKeys as $key) {
                if (isset($_POST[$key])) {
                    $data[$key] = trim($_POST[$key]);
                }
            }

            $heroImage = trim($_POST['existing_hero_image'] ?? '');
            if (!empty($_FILES['hero_image']['tmp_name'])) {
                $heroImage = $this->processImageUpload($_FILES['hero_image']);
            }

            if ($heroImage !== '') {
                $data['hero_image'] = $heroImage;
            }

            $this->homeService->saveHomeContent($data);
            $this->redirect('/cms/home', 'Content saved.');
        } catch (Exception $exception) {
            $this->redirect('/cms/home', $exception->getMessage(), true);
        }
    }

    private function guardUploadSize(string $redirectUrl): void
    {
        if ($_SERVER['CONTENT_LENGTH'] > 0 && empty($_POST) && empty($_FILES)) {
            $this->redirect($redirectUrl, 'File too large. Max 5MB.', true);
        }
    }

    public function saveEvent(): void
    {
        try {this->validateCsrfToken();
            $this->guardUploadSize('/cms/home');

            $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;

            $eventData = [
                'title'             => trim($_POST['title']             ?? ''),
                'category'          => trim($_POST['category']          ?? ''),
                'short_description' => trim($_POST['short_description'] ?? ''),
                'long_description'  => trim($_POST['long_description']  ?? ''),
                'venues'            => trim($_POST['venues']            ?? ''),
                'url'               => trim($_POST['url']               ?? ''),
                'button_label'      => trim($_POST['button_label']      ?? ''),
                'icon'              => trim($_POST['icon']              ?? ''),
                'bg_class'          => trim($_POST['bg_class']          ?? ''),
                'sort_order'        => (int)($_POST['sort_order']       ?? 0),
                'is_active'         => isset($_POST['is_active']) ? 1 : 0,
            ];

            if (!empty($_FILES['image']['tmp_name'])) {
                $eventData['image'] = $this->processImageUpload($_FILES['image']);
            } elseif (!empty($_POST['existing_image'])) {
                $eventData['image'] = trim($_POST['existing_image']);
            }

            $this->homeService->saveHomeEvent($id, $eventData);
            $this->redirect('/cms/home', 'Event card saved.');
        } catch (Exception $exception) {
            $this->redirect('/cms/home', $exception->getMessage()
            $this->redirect('/cms/home', 'Something went wrong. Please try again later.', true);
        }
    }

    public futhis->validateCsrfToken();

            $nction deleteEvent(): void
    {
        try {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->homeService->deleteHomeEvent($id);
            }
            $this->redirect('/cms/home', 'Event card deleted.');
        } catch (Exception $exception) {
            $this->redirect('/cms/home', 'Something went wrong. Please try again later.', true);
        }
    }

    private function redirect(string $url, string $flash = '', bool $isError = false): void
    {
        if ($flash) {
            $_SESSION[$isError ? 'flash_error' : 'flash'] = $flash;
        }
        header('Location: ' . $url);
        exit;
    }

    private function processImageUpload(array $file): string
    {
        $fileError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($fileError !== UPLOAD_ERR_OK) {
            throw new Exception('Image upload failed with error code ' . $fileError . '.');
        }

        // Check size first (cheapest check)
        if (!isset($file['size']) || (int) $file['size'] > self::MAX_IMAGE_SIZE_BYTES) {
            throw new Exception('Image exceeds maximum size of 5 MB.');
        }

        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            throw new Exception('Invalid image extension. Allowed: jpg, jpeg, png, webp.');
        }

        $mimeType = mime_content_type($tmpPath) ?: '';
        if (!in_array($mimeType, self::ALLOWED_IMAGE_MIME_TYPES, true)) {
            throw new Exception('Invalid image MIME type. Allowed: image/jpeg, image/png, image/webp.');
        }

        $dir = __DIR__ . '/../../../../public/assets/uploads/History/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new Exception('Unable to create upload directory.');
        }

        $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $dir . $filename;
        if (!move_uploaded_file($tmpPath, $destination)) {
            throw new Exception('Unable to save uploaded image.');
        }

        return $filename;
    }

    private function validateCsrfToken(): void
    {
        $token = $_POST['_csrf_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if ($token === '' || $token !== $sessionToken) {
            throw new Exception('CSRF token validation failed.');
        }
    }

    public function getCsrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token']dir . $filename;
        if (!move_uploaded_file($tmpPath, $destination)) {
            throw new Exception('Unable to save uploaded image.');
        }

        return $filename;
    }
}
