<?php

namespace App\Controllers\Cms\Home;

use App\Framework\Session;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\FileToLargeException;
use App\Models\Exceptions\InvalidKeyException;
use App\Models\Exceptions\QueryExecutionException;
use App\Services\HomeService;
use App\ViewModels\HomeEditViewModel;
use Exception;

class HomeCmsController
{
    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = HomeService::getInstance();
        $this->requireAdmin();
    }

    private function requireAdmin(): void
    {
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }

    // GET /cms/home
    public function index(): void
    {
        try {
            $viewModel = new HomeEditViewModel(
                $this->homeService->getHomeContent(),
                $this->homeService->getAllHomeEvents()
            );
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Something went wrong loading the homepage CMS.';
            $viewModel = new HomeEditViewModel([], []);
        }

        require __DIR__ . '/../../../Views/cms/home/home.php';
    }

    // POST /cms/home/save-content
    public function saveContent(): void
    {
        try {
            $this->homeService->saveHomeContent($_POST);
            $this->redirect('/cms/home', 'Content saved.');
        } catch (Exception $e) {
            $this->redirect('/cms/home', 'Something went wrong. Please try again.');
        }
    }

    // POST /cms/home/save-event
    public function saveEvent(): void
    {
        try {
            if (($_SERVER['CONTENT_LENGTH'] ?? 0) > 0 && empty($_POST) && empty($_FILES)) {
                throw new FileToLargeException('File too large. Max 8MB.');
            }

            $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;

            $this->homeService->saveHomeEvent($id, $_POST, $_FILES['image'] ?? null);

            $this->redirect('/cms/home', 'Event card saved.');
        } catch (EmptyFieldException|FileToLargeException|InvalidKeyException $e) {
            $this->redirect('/cms/home', $e->getMessage());
        } catch (Exception $e) {
            $this->redirect('/cms/home', 'Something went wrong. Please try again.');
        }
    }

    // POST /cms/home/delete-event
    public function deleteEvent(): void
    {
        try {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->homeService->deleteHomeEvent($id);
            }

            $this->redirect('/cms/home', 'Event card deleted.');
        } catch (Exception $e) {
            $this->redirect('/cms/home', 'Something went wrong. Please try again.');
        }
    }

    private function redirect(string $url, string $flash = ''): void
    {
        if ($flash) {
            $_SESSION['flash'] = $flash;
        }

        header('Location: ' . $url);
        exit;
    }
}
