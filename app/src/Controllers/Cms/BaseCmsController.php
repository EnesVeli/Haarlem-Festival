<?php

namespace App\Controllers\Cms;

use App\Framework\Session;

abstract class BaseCmsController
{
    public function __construct()
    {
        $this->requireAdmin();
    }

    protected function requireAdmin(): void
    {
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        if (!Session::isAdmin()) {
            http_response_code(403);
            echo '403 - Admin only';
            exit;
        }
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewsPath = __DIR__ . '/../../Views/';

        require $viewsPath . 'partials/header.php';
        require $viewsPath . $view . '.php';
        require $viewsPath . 'partials/footer.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}