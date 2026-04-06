<?php
namespace App\Controllers;

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

    /**
     * @param StoriesService $service Stories service
     */
    public function __construct(StoriesService $service)
    {
        $this->service = $service;
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
            'event' => $event,
            'ticketTypes' => $event ? $this->service->getTicketTypesForCms($event->event_id) : [],
        ]);
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

        $data = $this->validateAndBuildData($_POST);
        if ($data === null) {
            return;
        }

        $imagePath = $this->processImageUpload($_FILES['image'] ?? null, $data['image_path']);
        if ($imagePath === null) {
            return;
        }
        $data['image_path'] = $imagePath;

        $gallery1 = $this->processImageUpload($_FILES['gallery_image_1'] ?? null, $data['gallery_image_1']);
        $gallery2 = $this->processImageUpload($_FILES['gallery_image_2'] ?? null, $data['gallery_image_2']);
        if ($gallery1 === null || $gallery2 === null) {
            return;
        }
        $data['gallery_image_1'] = $gallery1;
        $data['gallery_image_2'] = $gallery2;

        if (!empty($_POST['event_id']) && is_numeric($_POST['event_id'])) {
            $this->service->updateEvent((int) $_POST['event_id'], $data);
        } else {
            $newEventId = $this->service->createEvent($data);
            $this->redirect('/cms/stories/edit?id=' . $newEventId);
            return;
        }

        if (isset($_POST['ticket_prices']) && is_array($_POST['ticket_prices']) && !empty($_POST['event_id']) && is_numeric($_POST['event_id'])) {
            $ticketTypes = $this->service->getTicketTypesForCms((int) $_POST['event_id']);
            $payAsYouLikeTypeIds = [];
            foreach ($ticketTypes as $ticketType) {
                if (!empty($ticketType['is_pay_as_you_like'])) {
                    $payAsYouLikeTypeIds[] = (int) $ticketType['type_id'];
                }
            }

            foreach ($_POST['ticket_prices'] as $typeId => $price) {
                $typeId = (int) $typeId;
                $price = (float) $price;
                if ($price < 0) {
                    continue;
                }
                if (in_array($typeId, $payAsYouLikeTypeIds, true)) {
                    continue;
                }
                $this->service->updateTicketTypePrice($typeId, $price);
            }
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
     * @return array<string, mixed>|null
     */
    private function validateAndBuildData(array $post): ?array
    {
        $name = trim((string) ($post['name'] ?? ''));
        $slug = trim((string) ($post['slug'] ?? ''));
        $description = trim((string) ($post['description'] ?? ''));
        $startTime = trim((string) ($post['start_time'] ?? ''));
        $endTime = trim((string) ($post['end_time'] ?? ''));
        $venueIdRaw = $post['venue_id'] ?? '';

        if (
            $name === '' ||
            $slug === '' ||
            $description === '' ||
            $startTime === '' ||
            $endTime === '' ||
            (string) $venueIdRaw === ''
        ) {
            http_response_code(422);
            echo 'Name, slug, description, start time, end time and venue are required.';
            return null;
        }

        $isValidTime = strtotime($startTime) !== false && strtotime($endTime) !== false;
        if (!$isValidTime) {
            http_response_code(422);
            echo 'Invalid start or end time.';
            return null;
        }

        if (!is_numeric((string) $venueIdRaw) || (int) $venueIdRaw < 1) {
            http_response_code(422);
            echo 'Venue ID must be a valid positive number.';
            return null;
        }

        $maxTicketsRaw = $post['max_tickets'] ?? 0;
        if (!is_numeric((string) $maxTicketsRaw) || (int) $maxTicketsRaw < 0) {
            http_response_code(422);
            echo 'Max tickets must be a valid number.';
            return null;
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'language' => trim((string) ($post['language'] ?? 'EN')),
            'age_group' => trim((string) ($post['age_group'] ?? 'All Ages')),
            'story_type' => trim((string) ($post['story_type'] ?? '')),
            'is_pay_as_you_like' => isset($post['is_pay_as_you_like']) ? 1 : 0,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'max_tickets' => (int) $maxTicketsRaw,
            'performer_name' => trim((string) ($post['performer_name'] ?? '')) ?: null,
            'performer_bio' => trim((string) ($post['performer_bio'] ?? '')) ?: null,
            'venue_id' => (int) $venueIdRaw,
            'image_path' => trim((string) ($post['existing_image'] ?? '')),
            'gallery_image_1' => trim((string) ($post['existing_gallery_1'] ?? '')),
            'gallery_image_2' => trim((string) ($post['existing_gallery_2'] ?? '')),
            'audio_preview_path' => trim((string) ($post['existing_audio'] ?? '')),
            'audio_title' => trim((string) ($post['audio_title'] ?? '')) ?: null,
            'audio_transcript' => trim((string) ($post['audio_transcript'] ?? '')) ?: null,
        ];
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
            http_response_code(422);
            echo 'Image upload failed.';
            return null;
        }

        if (!isset($imageFile['size']) || (int) $imageFile['size'] > self::MAX_IMAGE_SIZE_BYTES) {
            http_response_code(422);
            echo 'Image exceeds maximum size of 5 MB.';
            return null;
        }

        $originalName = (string) ($imageFile['name'] ?? '');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            http_response_code(422);
            echo 'Invalid image extension. Allowed: jpg, jpeg, png, webp.';
            return null;
        }

        $tmpPath = (string) ($imageFile['tmp_name'] ?? '');
        $mimeType = mime_content_type($tmpPath) ?: '';
        if (!in_array($mimeType, self::ALLOWED_IMAGE_MIME_TYPES, true)) {
            http_response_code(422);
            echo 'Invalid image MIME type.';
            return null;
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
