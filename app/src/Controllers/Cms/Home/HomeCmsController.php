<?php

namespace App\Controllers\Cms\Home;

use App\Services\HomeService;
use App\ViewModels\HomeEditViewModel;

class HomeCmsController
{
    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = new HomeService();
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

    // GET /cms/home
    public function index(): void
    {
        $viewModel = new HomeEditviewmodel(
            $this->homeService->getHomeContent(),
            $this->homeService->getAllHomeEvents()
        );

        require __DIR__ . '/../../../Views/cms/home/home.php';
    }

    // POST /cms/home/save-content
    public function saveContent(): void
    {
        $allowedKeys = [
            'hero_image', 'hero_title', 'hero_subtitle',
            'hero_description', 'program_title', 'program_description',
        ];

        $data = [];
        foreach ($allowedKeys as $key) {
            if (isset($_POST[$key])) {
                $data[$key] = trim($_POST[$key]);
            }
        }

        $this->homeService->saveHomeContent($data);

        $this->redirect('/cms/home', 'Content saved.');
    }

    // POST /cms/home/save-event
    public function saveEvent(): void
    {
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
            $dir      = __DIR__ . '/../../../../public/assets/uploads/History/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $filename)) {
                $eventData['image'] = $filename;
            }
        } elseif (!empty($_POST['existing_image'])) {
            $eventData['image'] = trim($_POST['existing_image']);
        }

        $this->homeService->saveHomeEvent($id, $eventData);

        $this->redirect('/cms/home', 'Event card saved.');
    }

    // POST /cms/home/delete-event
    public function deleteEvent(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->homeService->deleteHomeEvent($id);
        }

        $this->redirect('/cms/home', 'Event card deleted.');
    }

    private function redirect(string $url, string $flash = ''): void
    {
        if ($flash) $_SESSION['flash'] = $flash;
        header('Location: ' . $url);
        exit;
    }
}