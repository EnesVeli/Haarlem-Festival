<?php

namespace App\Controllers\Cms\History;

use App\Services\HistoryCmsService;
use Exception;

class HistoryCmsController
{
    private const MAX_IMAGE_SIZE_BYTES = 8_388_608; // 8 MB
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    private HistoryCmsService $service;

    public function __construct()
    {
        $this->service = HistoryCmsService::getInstance();
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
        // Ensure CSRF token is generated
        $csrfToken = $this->getCsrfToken();

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
        try {
            $this->validateCsrfToken();
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
        if (!isset($_POST['type'], $_POST['price']) || !is_numeric($_POST['price'])) {
            $this->redirect('/cms/history#tab-tickets', 'Missing or invalid ticket price.', true);
        }

        $type = (int)$_POST['type'];
        $priceInCents = (int)round((float)$_POST['price'] * 100);
        if ($priceInCents < 0) {
            $this->redirect('/cms/history#tab-tickets', 'Ticket price must be zero or positive.', true);
        }

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
        $this->guardUploadSize("/cms/history/detail/{$_POST['detail_id']}");

        $id = (int)($_POST['id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $image = $this->getCurrentSectionImage($id);

        if (!empty($_FILES['image_path']['tmp_name'])) {
            $image = $this->uploadFile($_FILES['image_path']);
        }

        $sectionType = trim($_POST['section_type'] ?? 'about');
        $allowedTypes = ['about', 'history', 'highlight', 'special'];
        if (!in_array($sectionType, $allowedTypes, true)) {
            $sectionType = 'about';
        }

        $data = [
            ':detail_id' => $detailId,
            ':section_type' => $sectionType,
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

    privathis->guardUploadSize("/cms/history/detail/{$_POST['detail_id']}");

        $te function addGallery(): void
    {
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $caption = trim($_POST['caption'] ?? '');
        $order = (int)($_POST['sort_order'] ?? 0);

        if (!empty($_FILES['image_path']['tmp_name'])) {
            $imagePath = $this->uploadFile($_FILES['image_path']);
            $this->service->addGalleryImage($detailId, $imagePath, $caption, $order);
        }

        $this->redirect("/cms/history/detail/{$detailId}", 'Image added.');
    }

    private function deleteGallery(): void
    {
        $galleryId = (int)($_POST['id'] ?? 0);
        $image = $this->service->getGalleryImageById($galleryId);
        if (!$image || empty($image['detail_id'])) {
            $this->redirect('/cms/history#tab-details', 'Image not found.', true);
        }
        $detailId = (int)$image['detail_id'];
        $this->service->deleteGalleryImage($galleryId);
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
    }Id = (int)($_POST['id'] ?? 0);
        $fact = $this->service->getFactById($factId);
        if (!$fact || empty($fact['detail_id'])) {
            $this->redirect('/cms/history#tab-details', 'Fact not found.', true);
        }
        $detailId = (int)$fact['detail_id'];
        $this->service->deleteFact($factId
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
        $fileError = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($fileError !== UPLOAD_ERR_OK) {
            throw new Exception('Image upload failed with error code ' . $fileError . '.');
        }

        $tmpPath = (string)($file['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new Exception('Invalid uploaded image.');
        }

        $size = (int)($file['size'] ?? 0);
        if ($size > self::MAX_IMAGE_SIZE_BYTES) {
            throw new Exception('Image exceeds maximum size of 8 MB.');
        }

        $extension = strtolower(pathinfo((string)($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            throw new Exception('Invalid image file type. Allowed: jpg, jpeg, png, webp.');
        }

        $mimeType = mime_content_type($tmpPath) ?: '';
        if (!in_array($mimeType, self::ALLOWED_IMAGE_MIME_TYPES, true)) {
            throw new Exception('Invalid image MIME type.');
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
    }validateCsrfToken(): void
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
        return $_SESSION['_csrf_token'];
    }

    private function 

    private function redirect(string $url, string $flash = '', bool $isError = false): void
    {
        if ($flash) {
            $_SESSION[$isError ? 'flash_error' : 'flash'] = $flash;
        }

        header('Location: ' . $url);
        exit;
    }
}
