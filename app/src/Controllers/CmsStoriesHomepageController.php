<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Interfaces\IStoriesHomepageService;
use App\ViewModels\CmsStoriesHomepageViewModel;

/**
 * CmsStoriesHomepageController — admin editor for the Stories homepage CMS content.
 *
 * Extends BaseController. Requires admin access.
 */
class CmsStoriesHomepageController extends BaseController
{
    /** @var IStoriesHomepageService */
    private IStoriesHomepageService $homepageService;

    /**
     * @param IStoriesHomepageService $homepageService
     */
    public function __construct(IStoriesHomepageService $homepageService)
    {
        // Guard — admin only
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        if (!Session::isAdmin()) {
            http_response_code(403);
            echo '403 - Admin only';
            exit;
        }

        $this->homepageService = $homepageService;
    }

    /**
     * GET /cms/stories/homepage — render the edit form.
     *
     * @param array $vars Route parameters (unused)
     * @return void
     */
    public function edit(array $vars = []): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $content = $this->homepageService->getStoriesContent();

        $viewModel = new CmsStoriesHomepageViewModel(
            $content,
            $_SESSION['csrf_token'],
            Session::flash('cms_success'),
            Session::flash('cms_error')
        );

        $this->render('cms/stories/homepage', [
            'viewModel' => $viewModel,
            'pageTitle'  => $viewModel->pageTitle,
        ]);
    }

    /**
     * POST /cms/stories/homepage — verify CSRF, handle image upload, save, redirect.
     *
     * @param array $vars Route parameters (unused)
     * @return void
     */
    public function update(array $vars = []): void
    {
        // CSRF check
        if (($_POST['csrf_token'] ?? '') !== ($_SESSION['csrf_token'] ?? '')) {
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
            'image_path' => $_POST['existing_image_path'] ?? '',
        ];

        // Handle optional image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/assets/images/stories/';
            $fileName  = time() . '_' . basename($_FILES['image']['name']);
            $fullDir   = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

            if (!is_dir($fullDir)) {
                mkdir($fullDir, 0777, true);
            }

            $destPath = $fullDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                $data['image_path'] = $uploadDir . $fileName;
            }
        }

        try {
            $this->homepageService->saveStoriesContent($data);
            $_SESSION['cms_success'] = 'Stories homepage content updated successfully.';
        } catch (\Exception $e) {
            $_SESSION['cms_error'] = 'Failed to update content: ' . $e->getMessage();
        }

        header('Location: /cms/stories/homepage');
        exit;
    }
}
