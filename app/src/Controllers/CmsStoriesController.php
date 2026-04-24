<?php
namespace App\Controllers;

use App\Models\StoryEvent;
use App\Services\StoriesService;

/**
 * Admin controller for managing Stories events in CMS.
 */
class CmsStoriesController extends BaseController
{
    private const MAX_IMAGE_SIZE_BYTES = 5_242_880; // 5 MB
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    private StoriesService $service;

    public function __construct()
    {
        $this->service = new StoriesService();
    }

    /**
     * Renders stories event list for admins.
     */
    public function index(): void
    {
        $this->mustBeAdmin();
        $this->ensureCsrfToken();

        $events = $this->service->getAllEvents();
        $this->render('cms/stories/index', ['events' => $events]);
    }

    /**
     * Renders create/edit form for a stories event.
     */
    public function edit(): void
    {
        $this->mustBeAdmin();
        $this->ensureCsrfToken();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $event = $id ? $this->service->getEventById($id) : null;

        $this->render('cms/stories/edit', [
            'event'       => $event,
            'cms_error'   => $_SESSION['cms_error'] ?? null,
        ]);
        unset($_SESSION['cms_error']);
    }

    /**
     * Handles create/update form submission.
     */
    public function save(): void
    {
        $this->mustBeAdmin();

        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $postedToken = $_POST['csrf_token'] ?? '';
        if (
            !is_string($sessionToken) ||
            !is_string($postedToken) ||
            $sessionToken === '' ||
            !hash_equals($sessionToken, $postedToken)
        ) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        $event = $this->validateAndBuildData($_POST);
        if ($event === null) {
            return;
        }

        $imagePath = $this->processImageUpload($_FILES['image'] ?? null, $event->image_path);
        if ($imagePath === null) {
            return;
        }
        $event->image_path = $imagePath;

        $gallery1 = $this->processImageUpload($_FILES['gallery_image_1'] ?? null, $event->gallery_image_1);
        $gallery2 = $this->processImageUpload($_FILES['gallery_image_2'] ?? null, $event->gallery_image_2);
        if ($gallery1 === null || $gallery2 === null) {
            return;
        }
        $event->gallery_image_1 = $gallery1;
        $event->gallery_image_2 = $gallery2;

        if (!empty($_POST['event_id']) && is_numeric($_POST['event_id'])) {
            $event->event_id = (int) $_POST['event_id'];

            $this->service->updateEvent($event);
        } else {
            $newEventId = $this->service->createEvent($event);
            $this->redirect('/cms/stories/edit?id=' . $newEventId);
            return;
        }

        $this->redirect('/cms/stories');
    }

    /**
     * Deletes an event by id.
     */
    public function delete(): void
    {
        $this->mustBeAdmin();

        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $postedToken = $_POST['csrf_token'] ?? '';
        if (
            !is_string($sessionToken) ||
            !is_string($postedToken) ||
            $sessionToken === '' ||
            !hash_equals($sessionToken, $postedToken)
        ) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        if (isset($_POST['id'])) {
            $this->service->deleteEvent((int) $_POST['id']);
        }

        $this->redirect('/cms/stories');
    }

    /**
     * Validates and normalizes POST payload for stories event.
     *
     * @param array<string, mixed> $post
     * @return ?StoryEvent
     */
    private function validateAndBuildData(array $post): ?StoryEvent
    {
        $name = trim((string) ($post['name'] ?? ''));
        $slug = trim((string) ($post['slug'] ?? ''));
        $description = trim((string) ($post['description'] ?? ''));
        $startTime = trim((string) ($post['start_time'] ?? ''));
        $endTime = trim((string) ($post['end_time'] ?? ''));

        if (
            $name === '' ||
            $slug === '' ||
            $description === '' ||
            $startTime === '' ||
            $endTime === ''
        ) {
            $_SESSION['cms_error'] = 'Name, slug, description, start time, end time and venue are required.';
            $this->redirect('/cms/stories/edit' . (!empty($post['event_id']) ? '?id=' . (int) $post['event_id'] : ''));
            exit;
        }

        $isValidTime = strtotime($startTime) !== false && strtotime($endTime) !== false;
        if (!$isValidTime) {
            $_SESSION['cms_error'] = 'Invalid start or end time.';
            $this->redirect('/cms/stories/edit' . (!empty($post['event_id']) ? '?id=' . (int) $post['event_id'] : ''));
            exit;
        }

        $maxTicketsRaw = $post['max_tickets'] ?? 0;
        if (!is_numeric((string) $maxTicketsRaw) || (int) $maxTicketsRaw < 0) {
            $_SESSION['cms_error'] = 'Max tickets must be a valid number.';
            $this->redirect('/cms/stories/edit' . (!empty($post['event_id']) ? '?id=' . (int) $post['event_id'] : ''));
            exit;
        }

        $is_pay_as_you_like = isset($post['is_pay_as_you_like']) ? 1 : 0;
        $price = $is_pay_as_you_like == 1 ? 0 : $post['price'] * 100;

        $event = new StoryEvent();

        $event->name = $name;
        $event->slug = $slug;
        $event->description = $description;
        $event->language = trim((string) ($post['language'] ?? 'EN'));
        $event->age_group = trim((string) ($post['age_group'] ?? 'All Ages'));
        $event->story_type = trim((string) ($post['story_type'] ?? ''));
        $event->is_pay_as_you_like = $is_pay_as_you_like;
        $event->price = $price;
        $event->start_time = $startTime;
        $event->end_time = $endTime;
        $event->max_tickets = (int)$maxTicketsRaw;
        $event->address_name = trim((string) ($post['address_name'] ?? '')) ?: "";
        $event->address_text = trim((string) ($post['address_text'] ?? '')) ?: "";
        $event->performer_name = trim((string) ($post['performer_name'] ?? '')) ?: null;
        $event->performer_bio = trim((string) ($post['performer_bio'] ?? '')) ?: null;
        $event->image_path = trim((string) ($post['existing_image'] ?? ''));
        $event->gallery_image_1 = trim((string) ($post['existing_gallery_1'] ?? ''));
        $event->gallery_image_2 = trim((string) ($post['existing_gallery_2'] ?? ''));     

        return $event;
    }

    /**
     * Validates and stores uploaded image.
     */
    private function processImageUpload(mixed $imageFile, string $existingImagePath): ?string
    {
        if (!is_array($imageFile) || !isset($imageFile['error'])) {
            return $existingImagePath;
        }

        if ((int) $imageFile['error'] === UPLOAD_ERR_NO_FILE) {
            return $existingImagePath;
        }

        if ((int) $imageFile['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['cms_error'] = 'Image upload failed.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/cms/stories');
            exit;
        }

        if (!isset($imageFile['size']) || (int) $imageFile['size'] > self::MAX_IMAGE_SIZE_BYTES) {
            $_SESSION['cms_error'] = 'Image exceeds maximum size of 5 MB.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/cms/stories');
            exit;
        }

        $originalName = (string) ($imageFile['name'] ?? '');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            $_SESSION['cms_error'] = 'Invalid image extension. Allowed: jpg, jpeg, png, webp.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/cms/stories');
            exit;
        }

        $tmpPath = (string) ($imageFile['tmp_name'] ?? '');
        $mimeType = mime_content_type($tmpPath) ?: '';
        if (!in_array($mimeType, self::ALLOWED_IMAGE_MIME_TYPES, true)) {
            $_SESSION['cms_error'] = 'Invalid image MIME type.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/cms/stories');
            exit;
        }

        $uploadDir = '/assets/images/stories/';
        $fullDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
        if (!is_dir($fullDir) && !mkdir($fullDir, 0755, true) && !is_dir($fullDir)) {
            http_response_code(500);
            echo 'Failed to create upload directory.';
            return null;
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
        $destination = $fullDir . $fileName;

        if (!move_uploaded_file($tmpPath, $destination)) {
            http_response_code(500);
            echo 'Failed to save uploaded image.';
            return null;
        }

        return $uploadDir . $fileName;
    }
}
