<?php

namespace App\Controllers\Cms\History;

use App\Repositories\HistoryCmsRepository;

class HistoryCmsController
{
    private HistoryCmsRepository $repo;

    public function __construct()
    {
        $this->repo = new HistoryCmsRepository();
        $this->requireAdmin();
    }

    // Blocks anyone who isn't logged in as admin
    private function requireAdmin(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }

    // GET /cms/history — main dashboard with all tabs
    public function index(): void
    {
        $highlights   = $this->repo->getAllHighlights();
        $tickets      = $this->repo->getAllTickets();
        $ticketPrices = $this->repo->getTicketPrices();
        $content      = $this->repo->getAllContentKeyed();
        $details      = $this->repo->getAllDetails();

        require __DIR__ . '/../../../Views/cms/history/index.php';
    }

    // GET /cms/history/detail/{id} — edit or create a highlight detail page
    public function detail(array $vars): void
    {
        $id         = (int)($vars['id'] ?? 0);
        $detail     = $id > 0 ? $this->repo->getDetailById($id) : [];
        $highlights = $this->repo->getAllHighlights();
        $sections   = $id > 0 ? $this->repo->getDetailSections($id) : [];
        $gallery    = $id > 0 ? $this->repo->getDetailGallery($id)  : [];
        $facts      = $id > 0 ? $this->repo->getDetailFacts($id)    : [];

        require __DIR__ . '/../../../Views/cms/history/detail.php';
    }

    // POST /cms/history/action — single entry point for all CMS form submissions
    public function action(): void
    {
        $action = $_POST['_action'] ?? '';

        switch ($action) {
            case 'save_highlight':   $this->saveHighlight();   break;
            case 'delete_highlight': $this->deleteHighlight(); break;
            case 'save_ticket':        $this->saveTicket();        break;
            case 'delete_ticket':      $this->deleteTicket();      break;
            case 'save_ticket_price':  $this->saveTicketPrice();  break;
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
    }

    // ── Highlights ────────────────────────────────────────────────────────

    private function saveHighlight(): void
    {
        if ($_SERVER['CONTENT_LENGTH'] > 0 && empty($_POST) && empty($_FILES)) {
            $this->redirect('/cms/history', 'File too large. Max 8MB.');
        }

        $id    = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $desc  = trim($_POST['description'] ?? '');

        if (!empty($_FILES['image']['tmp_name'])) {
            $image = $this->uploadFile($_FILES['image']);
        } else {
            $existing = $id > 0 ? $this->repo->getHighlightById($id) : [];
            $image    = $existing['image'] ?? null;
        }

        if ($id > 0) {
            $this->repo->updateHighlight($id, $title, $desc, $image);
        } else {
            $this->repo->createHighlight($title, $desc, $image);
        }

        $this->redirect('/cms/history', 'Highlight saved.');
    }

    private function deleteHighlight(): void
    {
        $this->repo->deleteHighlight((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history', 'Highlight deleted.');
    }

    // ── Tickets ───────────────────────────────────────────────────────────

    private function saveTicket(): void
    {
        $id    = (int)($_POST['id'] ?? 0);
        $slot  = trim($_POST['time_slot'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $spots = (int)($_POST['available_spots'] ?? 0);

        if ($id > 0) {
            $this->repo->updateTicket($id, $slot, $price, $spots);
        } else {
            $this->repo->createTicket($slot, $price, $spots);
        }

        $this->redirect('/cms/history#tab-tickets', 'Ticket slot saved.');
    }

    private function deleteTicket(): void
    {
        $this->repo->deleteTicket((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history#tab-tickets', 'Ticket deleted.');
    }

    private function saveTicketPrice(): void
    {
        $type  = $_POST['ticket_type'] ?? '';
        $price = (float)($_POST['price'] ?? 0);

        if (in_array($type, ['individual', 'family']) && $price >= 0) {
            $this->repo->updateTicketPrice($type, $price);
        }

        $this->redirect('/cms/history#tab-tickets', 'Ticket price updated.');
    }

    // ── Page Content ──────────────────────────────────────────────────────

    private function saveContent(): void
    {
        if ($_SERVER['CONTENT_LENGTH'] > 0 && empty($_POST) && empty($_FILES)) {
            $this->redirect('/cms/history#tab-content', 'File too large. Max 8MB.');
        }

        foreach (['hero', 'intro', 'walk', 'cta'] as $s) {
            $title    = trim($_POST["{$s}_title"]    ?? '');
            $subtitle = trim($_POST["{$s}_subtitle"] ?? '');

            $image    = $_POST["{$s}_img_current"]       ?? null;
            $imgLeft  = $_POST["{$s}_img_left_current"]  ?? null;
            $imgRight = $_POST["{$s}_img_right_current"] ?? null;

            if (!empty($_FILES["{$s}_image"]['tmp_name'])) {
                $image = $this->uploadFile($_FILES["{$s}_image"]);
            }
            if (!empty($_FILES["{$s}_image_left"]['tmp_name'])) {
                $imgLeft = $this->uploadFile($_FILES["{$s}_image_left"]);
            }
            if (!empty($_FILES["{$s}_image_right"]['tmp_name'])) {
                $imgRight = $this->uploadFile($_FILES["{$s}_image_right"]);
            }

            $this->repo->upsertContent($s, $title, $subtitle, $image, $imgLeft, $imgRight);
        }

        $this->redirect('/cms/history#tab-content', 'Content saved.');
    }

    // ── Details ───────────────────────────────────────────────────────────

    private function saveDetail(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        $data = [
            'highlight_id'     => (int)($_POST['highlight_id'] ?? 0),
            'slug'             => trim($_POST['slug'] ?? ''),
            'page_title'       => trim($_POST['page_title'] ?? ''),
            'hero_image'       => null,
            'location'         => trim($_POST['location'] ?? ''),
            'founded_year'     => trim($_POST['founded_year'] ?? ''),
            'style_type'       => trim($_POST['style_type'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ];

        if (!empty($_FILES['hero_image']['tmp_name'])) {
            $data['hero_image'] = $this->uploadFile($_FILES['hero_image']);
        } elseif ($id > 0) {
            $existing           = $this->repo->getDetailById($id);
            $data['hero_image'] = $existing['hero_image'] ?? null;
        }

        if ($id > 0) {
            $this->repo->updateDetail($id, $data);
            $this->redirect("/cms/history/detail/{$id}", 'Detail page saved.');
        } else {
            $newId = $this->repo->createDetail($data);
            $this->redirect("/cms/history/detail/{$newId}", 'Detail page created.');
        }
    }

    private function deleteDetail(): void
    {
        $this->repo->deleteDetail((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history#tab-details', 'Detail page deleted.');
    }

    // ── Sections ──────────────────────────────────────────────────────────

    private function saveSection(): void
    {
        $id       = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);

        if (!empty($_FILES['image_path']['tmp_name'])) {
            $image = $this->uploadFile($_FILES['image_path']);
        } else {
            $existing = $id > 0 ? $this->repo->getSectionById($id) : [];
            $image    = $existing['image_path'] ?? null;
        }

        $data = [
            ':detail_id'     => $detailId,
            ':section_type'  => trim($_POST['section_type']  ?? ''),
            ':section_title' => trim($_POST['section_title'] ?? ''),
            ':content'       => trim($_POST['content']       ?? ''),
            ':image_path'    => $image,
            ':sort_order'    => (int)($_POST['sort_order']   ?? 0),
        ];

        if ($id > 0) {
            $this->repo->updateSection($id, $data);
        } else {
            $this->repo->createSection($data);
        }

        $this->redirect("/cms/history/detail/{$detailId}", 'Section saved.');
    }

    private function deleteSection(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $this->repo->deleteSection((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Section deleted.');
    }

    // ── Gallery ───────────────────────────────────────────────────────────

    private function addGallery(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $caption  = trim($_POST['caption']    ?? '');
        $order    = (int)($_POST['sort_order'] ?? 0);

        if (!empty($_FILES['image_path']['tmp_name'])) {
            $imagePath = $this->uploadFile($_FILES['image_path']);
            $this->repo->createGalleryImage($detailId, $imagePath, $caption, $order);
        }

        $this->redirect("/cms/history/detail/{$detailId}", 'Image added.');
    }

    private function deleteGallery(): void
    {
        $img      = $this->repo->getGalleryImageById((int)($_POST['id'] ?? 0));
        $detailId = $img['detail_id'] ?? 0;
        $this->repo->deleteGalleryImage((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Image deleted.');
    }

    // ── Facts ─────────────────────────────────────────────────────────────

    private function saveFact(): void
    {
        $id       = (int)($_POST['id']        ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);

        $data = [
            ':detail_id'  => $detailId,
            ':icon'       => trim($_POST['icon']        ?? ''),
            ':label'      => trim($_POST['label']       ?? ''),
            ':value'      => trim($_POST['value']       ?? ''),
            ':sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];

        if ($id > 0) {
            $this->repo->updateFact($id, $data);
        } else {
            $this->repo->createFact($data);
        }

        $this->redirect("/cms/history/detail/{$detailId}", 'Fact saved.');
    }

    private function deleteFact(): void
    {
        $fact     = $this->repo->getFactById((int)($_POST['id'] ?? 0));
        $detailId = $fact['detail_id'] ?? 0;
        $this->repo->deleteFact((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Fact deleted.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function uploadFile(array $file): string
    {
        $dir = __DIR__ . '/../../../../public/assets/uploads/History/';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . $filename);

        return $filename;
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