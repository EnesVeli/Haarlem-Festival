<?php

namespace App\Controllers;

class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewsPath = __DIR__ . '/../Views/';

        require $viewsPath . 'partials/header.php';
        require $viewsPath . $view . '.php';
        require $viewsPath . 'partials/footer.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    protected function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function notFound(): void
    {
        http_response_code(404);
        echo '404 - Page not found';
        exit();
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}