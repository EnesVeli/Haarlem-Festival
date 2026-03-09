<?php
namespace App\Controllers;

class BaseController
{
    /**
     * Renders a view wrapped in header.php and footer.php partials.
     *
     * @param string $view  Relative path inside Views/ e.g. 'Stories/home'
     * @param array  $data  Variables to make available inside the view
     */
    protected function render(string $view, array $data = []): void
    {
        // Extract array keys as individual variables for the view
        // e.g. ['events' => $events] becomes $events inside the view file
        extract($data);

        $viewsPath = __DIR__ . '/../Views/';

        require $viewsPath . 'partials/header.php';
        require $viewsPath . $view . '.php';
        require $viewsPath . 'partials/footer.php';
    }

    /**
     * Redirects to a URL and stops execution.
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Sends a JSON response and stops execution.
     */
    protected function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Shows 404 and stops execution.
     */
    protected function notFound(): void
    {
        http_response_code(404);
        $this->render('partials/404');
        exit();
    }

    /**
     * Returns true if a user is logged in via session.
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Returns the current logged-in user data from session, or null.
     */
    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}