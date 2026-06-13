<?php

namespace App\Controllers\Cms\History;

use App\Framework\Session;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\FileToLargeException;
use App\Models\Exceptions\InvalidKeyException;
use App\Models\Exceptions\QueryExecutionException;
use App\Services\HistoryCmsService;
use App\ViewModels\Cms\History\HistoryCmsDetailViewModel;
use App\ViewModels\Cms\History\HistoryCmsIndexViewModel;
use Exception;

class HistoryCmsController
{
    private HistoryCmsService $service;

    public function __construct()
    {
        $this->service = HistoryCmsService::getInstance();
    }

    // Blocks anyone who isn't logged in as admin
    private function requireAdmin(): void
    {
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }

    // GET /cms/history — main dashboard with all tabs
    public function index(): void
    {
        $this->requireAdmin();

        try {
            $viewModel = new HistoryCmsIndexViewModel(
                $this->service->getAllHighlights(),
                $this->service->getAllContentKeyed(),
                $this->service->getAllDetails(),
                $this->service->getIndividualPrice(),
                $this->service->getFamilyPrice()
            );
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Something went wrong loading the History CMS.';
            $viewModel = HistoryCmsIndexViewModel::empty();
        }

        require __DIR__ . '/../../../Views/cms/history/index.php';
    }

    // GET /cms/history/detail/{id} — edit or create a highlight detail page
    public function detail(array $vars): void
    {
        $this->requireAdmin();

        $id = (int)($vars['id'] ?? 0);

        try {
            $detail = $id > 0 ? $this->service->getDetailById($id) : null;

            $viewModel = new HistoryCmsDetailViewModel(
                $detail,
                $this->service->getAllHighlights(),
                $id > 0 ? $this->service->getDetailSections($id) : [],
                $id > 0 ? $this->service->getDetailGallery($id) : [],
                $id > 0 ? $this->service->getDetailFacts($id) : []
            );
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Something went wrong loading this detail page.';
            $this->redirect('/cms/history#tab-details');
            return;
        }

        require __DIR__ . '/../../../Views/cms/history/detail.php';
    }

    // POST /cms/history/action — single entry point for all CMS form submissions
    public function action(): void
    {
        $this->requireAdmin();

        $action = $_POST['_action'] ?? '';

        try {
            switch ($action) {
                case 'save_highlight':   $this->saveHighlight();   break;
                case 'delete_highlight': $this->deleteHighlight(); break;
                case 'save_ticket_price': $this->saveTicketPrice(); break;
                case 'save_content':     $this->saveContent();     break;
                case 'save_detail':      $this->saveDetail();      break;
                case 'delete_detail':    $this->deleteDetail();    break;
                case 'save_section':     $this->saveSection();     break;
                case 'delete_section':   $this->deleteSection();   break;
                case 'add_gallery':      $this->addGallery();      break;
                case 'delete_gallery':   $this->deleteGallery();   break;
                case 'save_fact':        $this->saveFact();        break;
                case 'delete_fact':      $this->deleteFact();      break;
                default:                 $this->redirect('/cms/history');
            }
        } catch (EmptyFieldException|FileToLargeException|InvalidKeyException $e) {
            // Validation-style errors: safe to show the message to the admin.
            $this->redirect($this->fallbackRedirect(), $e->getMessage());
        } catch (Exception $e) {
            // Anything else (DB failures etc.): never leak details to the user.
            $this->redirect($this->fallbackRedirect(), 'Something went wrong. Please try again.');
        }
    }

    // ── Highlights ────────────────────────────────────────────────────────

    private function saveHighlight(): void
    {
        if (($_SERVER['CONTENT_LENGTH'] ?? 0) > 0 && empty($_POST) && empty($_FILES)) {
            throw new FileToLargeException('File too large. Max 8MB.');
        }

        $this->service->saveHighlight(
            (int)($_POST['id'] ?? 0),
            $_POST['title'] ?? '',
            $_POST['description'] ?? '',
            $_FILES['image'] ?? null
        );

        $this->redirect('/cms/history', 'Highlight saved.');
    }

    private function deleteHighlight(): void
    {
        $this->service->deleteHighlight((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history', 'Highlight deleted.');
    }

    // ── Tickets ───────────────────────────────────────────────────────────

    private function saveTicketPrice(): void
    {
        if (!isset($_POST['type']) || !isset($_POST['price'])) {
            throw new EmptyFieldException('Ticket type and price are required.');
        }

        if (!is_numeric($_POST['price'])) {
            throw new EmptyFieldException('Price must be a number.');
        }

        $this->service->saveTicketPrice((int)$_POST['type'], (float)$_POST['price']);

        $this->redirect('/cms/history#tab-tickets', 'Ticket price updated.');
    }

    // ── Page Content ─────────────────────────────────────────────────────

    private function saveContent(): void
    {
        if (($_SERVER['CONTENT_LENGTH'] ?? 0) > 0 && empty($_POST) && empty($_FILES)) {
            throw new FileToLargeException('File too large. Max 8MB.');
        }

        $this->service->saveContent($_POST, $_FILES);

        $this->redirect('/cms/history#tab-content', 'Content saved.');
    }

    // ── Details ──────────────────────────────────────────────────────────

    private function saveDetail(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $savedId = $this->service->saveDetail($id, $_POST, $_FILES['hero_image'] ?? null);

        $message = $id > 0 ? 'Detail page saved.' : 'Detail page created.';
        $this->redirect("/cms/history/detail/{$savedId}", $message);
    }

    private function deleteDetail(): void
    {
        $this->service->deleteDetail((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history#tab-details', 'Detail page deleted.');
    }

    // ── Sections ─────────────────────────────────────────────────────────

    private function saveSection(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);

        $this->service->saveSection($id, $detailId, $_POST, $_FILES['image_path'] ?? null);

        $this->redirect("/cms/history/detail/{$detailId}", 'Section saved.');
    }

    private function deleteSection(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $this->service->deleteSection((int)($_POST['id'] ?? 0), $detailId);

        $this->redirect("/cms/history/detail/{$detailId}", 'Section deleted.');
    }

    // ── Gallery ──────────────────────────────────────────────────────────

    private function addGallery(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);

        $this->service->addGalleryImage(
            $detailId,
            $_POST['caption'] ?? '',
            (int)($_POST['sort_order'] ?? 0),
            $_FILES['image_path'] ?? null
        );

        $this->redirect("/cms/history/detail/{$detailId}", 'Image added.');
    }

    private function deleteGallery(): void
    {
        $detailId = $this->service->deleteGalleryImage((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Image deleted.');
    }

    // ── Facts ────────────────────────────────────────────────────────────

    private function saveFact(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);

        $this->service->saveFact($id, $detailId, $_POST);

        $this->redirect("/cms/history/detail/{$detailId}", 'Fact saved.');
    }

    private function deleteFact(): void
    {
        $detailId = $this->service->deleteFact((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Fact deleted.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * Best-effort redirect target for the generic error catch block, based on
     * the detail_id the form submission referenced (if any).
     */
    private function fallbackRedirect(): string
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);

        return $detailId > 0 ? "/cms/history/detail/{$detailId}" : '/cms/history';
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
