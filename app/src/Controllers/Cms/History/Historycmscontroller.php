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

    private function requireAdmin(): void
     {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /login');
        exit;
    }
    }

    // GET /cms/history
    public function index(): void
    {
        $highlights = $this->repo->getAllHighlights();
        $content    = $this->repo->getAllContentKeyed();
        $tickets    = $this->repo->getAllTickets();
        $details    = $this->repo->getAllDetails();
        require __DIR__ . '/../../../Views/cms/history/index.php';
    }

    // GET /cms/history/detail/{id}   (id=0 = new)
    public function detail(array $vars): void
    {
        $id         = (int)($vars['id'] ?? 0);
        $highlights = $this->repo->getAllHighlights();
        $detail     = $id ? $this->repo->getDetailById($id) : null;
        $sections   = $id ? $this->repo->getDetailSections($id) : [];
        $gallery    = $id ? $this->repo->getDetailGallery($id) : [];
        $facts      = $id ? $this->repo->getDetailFacts($id) : [];
        require __DIR__ . '/../../../Views/cms/history/detail.php';
    }

    // POST /cms/history/action  — single endpoint for everything
    public function action(): void
    {
        $act = $_POST['_action'] ?? '';
        match ($act) {
            'save_content'     => $this->saveContent(),
            'save_highlight'   => $this->saveHighlight(),
            'delete_highlight' => $this->deleteHighlight(),
            'save_ticket'      => $this->saveTicket(),
            'delete_ticket'    => $this->deleteTicket(),
            'save_detail'      => $this->saveDetail(),
            'delete_detail'    => $this->deleteDetail(),
            'save_section'     => $this->saveSection(),
            'delete_section'   => $this->deleteSection(),
            'add_gallery'      => $this->addGallery(),
            'delete_gallery'   => $this->deleteGallery(),
            'save_fact'        => $this->saveFact(),
            'delete_fact'      => $this->deleteFact(),
            default            => $this->redirect('/cms/history'),
        };
    }

    // ── content ──────────────────────────────────────────────────────────────

    private function saveContent(): void
    {
        foreach (['hero', 'intro', 'walk', 'cta'] as $s) {
            $existing   = $this->repo->getContentBySection($s);
            $image      = $this->handleUpload($s . '_image')       ?? ($existing['image']       ?? null);
            $imageLeft  = $this->handleUpload($s . '_image_left')  ?? ($existing['image_left']  ?? null);
            $imageRight = $this->handleUpload($s . '_image_right') ?? ($existing['image_right'] ?? null);
            $this->repo->upsertContent(
                $s,
                trim($_POST[$s . '_title']    ?? ''),
                trim($_POST[$s . '_subtitle'] ?? ''),
                $image, $imageLeft, $imageRight
            );
        }
        $this->redirect('/cms/history', 'Page content saved.');
    }

    // ── highlights ────────────────────────────────────────────────────────────

    private function saveHighlight(): void
    {
        $id    = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $desc  = trim($_POST['description'] ?? '');
        $image = $this->handleUpload('image');
        if ($id) {
            $image = $image ?? $this->repo->getHighlightById($id)['image'];
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

    // ── tickets ───────────────────────────────────────────────────────────────

    private function saveTicket(): void
    {
        $id    = (int)($_POST['id'] ?? 0);
        $ts    = trim($_POST['time_slot'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $spots = (int)($_POST['available_spots'] ?? 0);
        $id ? $this->repo->updateTicket($id, $ts, $price, $spots)
            : $this->repo->createTicket($ts, $price, $spots);
        $this->redirect('/cms/history', 'Ticket slot saved.');
    }

    private function deleteTicket(): void
    {
        $this->repo->deleteTicket((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history', 'Ticket slot deleted.');
    }

    // ── details ───────────────────────────────────────────────────────────────

    private function saveDetail(): void
    {
        $id   = (int)($_POST['id'] ?? 0);
        $data = $this->collectDetailPost();
        if ($id) {
            $data['hero_image'] = $this->handleUpload('hero_image') ?? $this->repo->getDetailById($id)['hero_image'];
            $this->repo->updateDetail($id, $data);
            $this->redirect('/cms/history/detail/' . $id, 'Detail page saved.');
        } else {
            $data['hero_image'] = $this->handleUpload('hero_image') ?? '';
            $newId = $this->repo->createDetail($data);
            $this->redirect('/cms/history/detail/' . $newId, 'Detail page created.');
        }
    }

    private function deleteDetail(): void
    {
        $this->repo->deleteDetail((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history', 'Detail page deleted.');
    }

    // ── sections ──────────────────────────────────────────────────────────────

    private function saveSection(): void
    {
        $id       = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $data = [
            ':detail_id'     => $detailId,
            ':section_type'  => $_POST['section_type'] ?? 'about',
            ':section_title' => trim($_POST['section_title'] ?? ''),
            ':content'       => trim($_POST['content'] ?? ''),
            ':sort_order'    => (int)($_POST['sort_order'] ?? 0),
        ];
        if ($id) {
            $data[':image_path'] = $this->handleUpload('image_path') ?? $this->repo->getSectionById($id)['image_path'];
            $this->repo->updateSection($id, $data);
        } else {
            $data[':image_path'] = $this->handleUpload('image_path') ?? '';
            $this->repo->createSection($data);
        }
        $this->redirect('/cms/history/detail/' . $detailId, 'Section saved.');
    }

    private function deleteSection(): void
    {
        $id      = (int)($_POST['id'] ?? 0);
        $section = $this->repo->getSectionById($id);
        $this->repo->deleteSection($id);
        $this->redirect('/cms/history/detail/' . ($section['detail_id'] ?? 0), 'Section deleted.');
    }

    // ── gallery ───────────────────────────────────────────────────────────────

    private function addGallery(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $path     = $this->handleUpload('image_path');
        if ($path) {
            $this->repo->createGalleryImage($detailId, $path, trim($_POST['caption'] ?? ''), (int)($_POST['sort_order'] ?? 0));
        }
        $this->redirect('/cms/history/detail/' . $detailId, 'Image added.');
    }

    private function deleteGallery(): void
    {
        $id    = (int)($_POST['id'] ?? 0);
        $image = $this->repo->getGalleryImageById($id);
        $this->repo->deleteGalleryImage($id);
        $this->redirect('/cms/history/detail/' . ($image['detail_id'] ?? 0), 'Image deleted.');
    }

    // ── facts ─────────────────────────────────────────────────────────────────

    private function saveFact(): void
    {
        $id       = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $data = [
            ':detail_id'  => $detailId,
            ':icon'       => trim($_POST['icon'] ?? ''),
            ':label'      => trim($_POST['label'] ?? ''),
            ':value'      => trim($_POST['value'] ?? ''),
            ':sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];
        $id ? $this->repo->updateFact($id, $data) : $this->repo->createFact($data);
        $this->redirect('/cms/history/detail/' . $detailId, 'Fact saved.');
    }

    private function deleteFact(): void
    {
        $id   = (int)($_POST['id'] ?? 0);
        $fact = $this->repo->getFactById($id);
        $this->repo->deleteFact($id);
        $this->redirect('/cms/history/detail/' . ($fact['detail_id'] ?? 0), 'Fact deleted.');
    }

    

    private function collectDetailPost(): array
    {
        return [
            'highlight_id'     => (int)($_POST['highlight_id'] ?? 0),
            'slug'             => trim($_POST['slug'] ?? ''),
            'page_title'       => trim($_POST['page_title'] ?? ''),
            'location'         => trim($_POST['location'] ?? ''),
            'founded_year'     => trim($_POST['founded_year'] ?? ''),
            'style_type'       => trim($_POST['style_type'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ];
    }

    private function handleUpload(string $field): ?string
    {
        if (empty($_FILES[$field]['tmp_name'])) return null;
        $dir = __DIR__ . '/../../../../public/assets/uploads/History/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ext      = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES[$field]['tmp_name'], $dir . $filename);
        return $filename;
    }

    private function redirect(string $url, string $flash = ''): void
    {
        if ($flash) $_SESSION['flash'] = $flash;
        header('Location: ' . $url);
        exit;
    }
}