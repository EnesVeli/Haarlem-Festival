<?php
namespace App\Controllers;

use App\Interfaces\IStoriesHomepageService;
use App\Services\StoriesHomepageService;
use App\ViewModels\CmsStoriesHomepageViewModel;

/**
 * CmsStoriesHomepageController — admin editor for the Stories homepage CMS content.
 *
 * Extends BaseController. Requires admin access.
 */
class CmsStoriesHomepageController extends BaseController
{
    private const MAX_IMAGE_SIZE_BYTES = 5_242_880; // 5 MB
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    /** @var IStoriesHomepageService */
    private IStoriesHomepageService $homepageService;

    public function __construct()
    {
        $this->homepageService = new StoriesHomepageService();
    }

    /**
     * GET /cms/stories/homepage — render the edit form.
     *
     * @param array $vars Route parameters 
     * @return void
     */
    public function edit(array $vars = []): void
    {
$this->mustBeAdmin();
    $this->ensureCsrfToken();

    $content = $this->homepageService->getStoriesContent();

    $viewModel = new CmsStoriesHomepageViewModel(
        $content,
        $_SESSION['csrf_token'],
        $_SESSION['cms_success'] ?? null,
        $_SESSION['cms_error'] ?? null
    );
    unset($_SESSION['cms_success'], $_SESSION['cms_error']);

        $this->render('cms/stories/homepage', [
            'viewModel' => $viewModel,
            'pageTitle'  => $viewModel->pageTitle,
        ]);
    }

    /**
     * POST /cms/stories/homepage — verify CSRF, handle image upload, save, redirect.
     *
     * @param array $vars 
     * @return void
     */
    public function update(array $vars = []): void
    {
        $this->mustBeAdmin();

        // Verify CSRF token
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

        // Collect form data
        $data = [
            'title'      => $_POST['title']      ?? '',
            'subtitle'   => $_POST['subtitle']    ?? '',
            'body_html'  => $_POST['body_html']   ?? '',
            'quote_text' => $_POST['quote_text']  ?? '',
            'cta_text'   => $_POST['cta_text']    ?? '',
            'ticket_info_title_1' => $_POST['ticket_info_title_1'] ?? '',
            'ticket_info_body_1'  => $_POST['ticket_info_body_1'] ?? '',
            'ticket_info_note_1'  => $_POST['ticket_info_note_1'] ?? '',
            'ticket_info_title_2' => $_POST['ticket_info_title_2'] ?? '',
            'ticket_info_body_2'  => $_POST['ticket_info_body_2'] ?? '',
            'cta_description'     => $_POST['cta_description'] ?? '',
            'image_path' => $_POST['existing_image_path'] ?? '',
        ];

        $imagePath = $this->processImageUpload($_FILES['image'] ?? null, (string) $data['image_path']);
        if ($imagePath === null) {
            return;
        }
        $data['image_path'] = $imagePath;

        try {
            $this->homepageService->saveStoriesContent($data);
            $_SESSION['cms_success'] = 'Stories homepage content updated successfully.';
        } catch (\Exception $e) {
            $_SESSION['cms_error'] = 'Failed to update content: ' . $e->getMessage();
        }

        header('Location: /cms/stories/homepage');
        exit;
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