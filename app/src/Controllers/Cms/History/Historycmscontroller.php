<?php

namespace App\Controllers\Cms\History;

use App\Services\HistoryCmsService;
use Exception;

class HistoryCmsController
{
    private HistoryCmsService $service;

    public function __construct()
    {
        $this->service = HistoryCmsService::getInstance();
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
        $this->requireAdmin();

        try {
            $highlights = $this->service->getAllHighlights();
            $content = $this->service->getAllContentKeyed();
            $details = $this->service->getAllDetails();
            $individual_price = $this->service->getIndividualPrice();
            $family_price = $this->service->getFamilyPrice();
        } catch (Exception $exception) {
            $_SESSION['flash_error'] = 'Something went wrong. Please try again later.';
            $highlights = [];
            $content = [];
            $details = [];
            $individual_price = 0;
            $family_price = 0;
        }

        require __DIR__ . '/../../../Views/cms/history/index.php';
    }

    public function detail(array $vars): void
    {
        $this->requireAdmin();

        try {
            $id = (int)($vars['id'] ?? 0);
            $detail = $id > 0 ? $this->service->getDetailById($id) : [];
            $highlights = $this->service->getAllHighlights();
            $sections = $id > 0 ? $this->service->getDetailSections($id) : [];
            $gallery = $id > 0 ? $this->service->getDetailGallery($id) : [];
            $facts = $id > 0 ? $this->service->getDetailFacts($id) : [];
        } catch (Exception $exception) {
            $_SESSION['flash_error'] = 'Something went wrong. Please try again later.';
            $id = 0;
            $detail = [];
            $highlights = [];
            $sections = [];
            $gallery = [];
            $facts = [];
        }

        require __DIR__ . '/../../../Views/cms/history/detail.php';
    }

    public function action(): void
    {
        $this->requireAdmin();

        try {
            $action = $_POST['_action'] ?? '';

            switch ($action) {
                case 'save_highlight':
                    $this->saveHighlight();
                    break;
                case 'delete_highlight':
                    $this->deleteHighlight();
                    break;
                case 'save_ticket_price':
                    $this->saveTicketPrice();
                    break;
                case 'save_content':
                    $this->saveContent();
                    break;
                case 'save_detail':
                    $this->saveDetail();
                    break;
                case 'delete_detail':
                    $this->deleteDetail();
                    break;
                case 'save_section':
                    $this->saveSection();
                    break;
                case 'delete_section':
                    $this->deleteSection();
                    break;
                case 'add_gallery':
                    $this->addGallery();
                    break;
                case 'delete_gallery':
                    $this->deleteGallery();
                    break;
                case 'save_fact':
                    $this->saveFact();
                    break;
                case 'delete_fact':
                    $this->deleteFact();
                    break;
                default:
                    $this->redirect('/cms/history');
            }
        } catch (Exception $exception) {
            $this->redirect('/cms/history', 'Something went wrong. Please try again later.', true);
        }
    }

    private function saveHighlight(): void
    {
        $this->guardUploadSize('/cms/history');

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image = $this->getCurrentHighlightImage($id);

        if (!empty($_FILES['image']['tmp_name'])) {
            $image = $this->uploadFile($_FILES['image']);
        }

        $this->service->saveHighlight($id > 0 ? $id : null, $title, $description, $image);
        $this->redirect('/cms/history', 'Highlight saved.');
    }

    private function getCurrentHighlightImage(int $id): ?string
    {
        if ($id <= 0) {
            return null;
        }

        $existing = $this->service->getHighlightById($id);
        return $existing['image'] ?? null;
    }

    private function deleteHighlight(): void
    {
        $this->service->deleteHighlight((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history', 'Highlight deleted.');
    }

    private function saveTicketPrice(): void
    {
        if (!isset($_POST['type'], $_POST['price'])) {
            $this->redirect('/cms/history#tab-tickets', 'Missing ticket price data.', true);
        }

        $type = (int)$_POST['type'];
        $priceInCents = (int)round((float)$_POST['price'] * 100);

        $this->service->updateTicketPrice($type, $priceInCents);
        $this->redirect('/cms/history#tab-tickets', 'Ticket price updated.');
    }

    private function saveContent(): void
    {
        $this->guardUploadSize('/cms/history#tab-content');

        foreach (['hero', 'intro', 'walk', 'cta'] as $section) {
            $this->saveContentBlock($section);
        }

        $this->redirect('/cms/history#tab-content', 'Content saved.');
    }

    private function saveContentBlock(string $section): void
    {
        $image = $_POST["{$section}_img_current"] ?? null;
        $imageLeft = $_POST["{$section}_img_left_current"] ?? null;
        $imageRight = $_POST["{$section}_img_right_current"] ?? null;

        if (!empty($_FILES["{$section}_image"]['tmp_name'])) {
            $image = $this->uploadFile($_FILES["{$section}_image"]);
        }
        if (!empty($_FILES["{$section}_image_left"]['tmp_name'])) {
            $imageLeft = $this->uploadFile($_FILES["{$section}_image_left"]);
        }
        if (!empty($_FILES["{$section}_image_right"]['tmp_name'])) {
            $imageRight = $this->uploadFile($_FILES["{$section}_image_right"]);
        }

        $this->service->saveContentSection(
            $section,
            trim($_POST["{$section}_title"] ?? ''),
            trim($_POST["{$section}_subtitle"] ?? ''),
            $image,
            $imageLeft,
            $imageRight
        );
    }

    private function saveDetail(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $data = $this->detailFormData($id);
        $savedId = $this->service->saveDetail($id > 0 ? $id : null, $data);

        $this->redirect("/cms/history/detail/{$savedId}", $id > 0 ? 'Detail page saved.' : 'Detail page created.');
    }

    /**
     * @return array<string, mixed>
     */
    private function detailFormData(int $id): array
    {
        $heroImage = null;

        if (!empty($_FILES['hero_image']['tmp_name'])) {
            $heroImage = $this->uploadFile($_FILES['hero_image']);
        } elseif ($id > 0) {
            $existing = $this->service->getDetailById($id);
            $heroImage = $existing['hero_image'] ?? null;
        }

        return [
            'highlight_id' => (int)($_POST['highlight_id'] ?? 0),
            'slug' => trim($_POST['slug'] ?? ''),
            'page_title' => trim($_POST['page_title'] ?? ''),
            'hero_image' => $heroImage,
            'location' => trim($_POST['location'] ?? ''),
            'founded_year' => trim($_POST['founded_year'] ?? ''),
            'style_type' => trim($_POST['style_type'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ];
    }

    private function deleteDetail(): void
    {
        $this->service->deleteDetail((int)($_POST['id'] ?? 0));
        $this->redirect('/cms/history#tab-details', 'Detail page deleted.');
    }

    private function saveSection(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $image = $this->getCurrentSectionImage($id);

        if (!empty($_FILES['image_path']['tmp_name'])) {
            $image = $this->uploadFile($_FILES['image_path']);
        }

        $data = [
            ':detail_id' => $detailId,
            ':section_type' => trim($_POST['section_type'] ?? ''),
            ':section_title' => trim($_POST['section_title'] ?? ''),
            ':content' => trim($_POST['content'] ?? ''),
            ':image_path' => $image,
            ':sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];

        $this->service->saveSection($id > 0 ? $id : null, $data);
        $this->redirect("/cms/history/detail/{$detailId}", 'Section saved.');
    }

    private function getCurrentSectionImage(int $id): ?string
    {
        if ($id <= 0) {
            return null;
        }

        $existing = $this->service->getSectionById($id);
        return $existing['image_path'] ?? null;
    }

    private function deleteSection(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $this->service->deleteSection((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Section deleted.');
    }

    private function addGallery(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $caption = trim($_POST['caption'] ?? '');
        $order = (int)($_POST['sort_order'] ?? 0);

        if (!empty($_FILES['image_path']['tmp_name'])) {
            $this->service->addGalleryImage($detailId, $this->uploadFile($_FILES['image_path']), $caption, $order);
        }

        $this->redirect("/cms/history/detail/{$detailId}", 'Image added.');
    }

    private function deleteGallery(): void
    {
        $image = $this->service->getGalleryImageById((int)($_POST['id'] ?? 0));
        $detailId = (int)($image['detail_id'] ?? 0);
        $this->service->deleteGalleryImage((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Image deleted.');
    }

    private function saveFact(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $data = [
            ':detail_id' => $detailId,
            ':icon' => trim($_POST['icon'] ?? ''),
            ':label' => trim($_POST['label'] ?? ''),
            ':value' => trim($_POST['value'] ?? ''),
            ':sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];

        $this->service->saveFact($id > 0 ? $id : null, $data);
        $this->redirect("/cms/history/detail/{$detailId}", 'Fact saved.');
    }

    private function deleteFact(): void
    {
        $fact = $this->service->getFactById((int)($_POST['id'] ?? 0));
        $detailId = (int)($fact['detail_id'] ?? 0);
        $this->service->deleteFact((int)($_POST['id'] ?? 0));
        $this->redirect("/cms/history/detail/{$detailId}", 'Fact deleted.');
    }

    private function guardUploadSize(string $redirectUrl): void
    {
        if ($_SERVER['CONTENT_LENGTH'] > 0 && empty($_POST) && empty($_FILES)) {
            $this->redirect($redirectUrl, 'File too large. Max 8MB.', true);
        }
    }

    private function uploadFile(array $file): string
    {
        $dir = __DIR__ . '/../../../../public/assets/uploads/history/';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . $filename);

        return $filename;
    }

    private function redirect(string $url, string $flash = '', bool $isError = false): void
    {
        if ($flash) {
            $_SESSION[$isError ? 'flash_error' : 'flash'] = $flash;
        }

        header('Location: ' . $url);
        exit;
    }
}
