<?php

namespace App\Controllers;

use App\Framework\Session;
use App\Repositories\CmsContentRepository;
use PDO;

class CmsJazzController
{
    private CmsContentRepository $cmsRepo;

    public function __construct()
{
    $this->cmsRepo = new CmsContentRepository();
    $this->requireAdmin();
}

    private function requireAdmin(): void
    {
        $user = Session::user();

        if (!$user) {
            header('Location: /login');
            exit;
        }

        // your User.role is enum('customer','employee','admin')
        if (($user['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo '403 - Admin only';
            exit;
        }
    }

    public function index(): void
    {
        $this->requireAdmin();

        $pageTitle = 'CMS - Jazz Homepage';

        // show even inactive blocks in CMS
        $experiences = $this->cmsRepo->getBlocks('jazz_home', 'experience', false);
        $performers  = $this->cmsRepo->getBlocksWithPerformerName('jazz_home', 'performer', false);
        $recs        = $this->cmsRepo->getBlocks('jazz_home', 'recommendation', false);

        $user = Session::user();

        require __DIR__ . '/../Views/cms/jazz_home.php';
    }

    public function new(): void
    {
        $this->requireAdmin();

        $pageTitle = 'CMS - New Block';
        $user = Session::user();

        $block = [
            'id' => 0,
            'page_key' => 'jazz_home',
            'block_type' => ($_GET['type'] ?? 'experience'),
            'performer_id' => 0,
            'title' => '',
            'subtitle' => null,
            'body' => null,
            'url' => null,
            'image_path' => null,
            'sort_order' => 0,
            'is_active' => 1,
        ];

        $performerOptions = $this->cmsRepo->getAllJazzPerformers();

        require __DIR__ . '/../Views/cms/edit_block.php';
    }

    public function create(): void
    {
        $this->requireAdmin();

        $data = [
            'page_key'     => 'jazz_home',
            'block_type'   => $_POST['block_type'] ?? 'experience',
            'performer_id' => (int)($_POST['performer_id'] ?? 0),
            'title'        => trim($_POST['title'] ?? ''),
            'subtitle'     => ($_POST['subtitle'] ?? null),
            'body'         => ($_POST['body'] ?? null),
            'url'          => ($_POST['url'] ?? null),
            'image_path'   => ($_POST['image_path'] ?? null),
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
            'is_active'    => (int)($_POST['is_active'] ?? 1),
        ];

        $this->cmsRepo->create($data);

        header('Location: /cms/jazz/home');
        exit;
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Block not found';
            return;
        }

        $block = $this->cmsRepo->getById($id);
        if (!$block) {
            http_response_code(404);
            echo '404 - Block not found';
            return;
        }

        $pageTitle = 'CMS - Edit Block';
        $user = Session::user();

        $performerOptions = $this->cmsRepo->getAllJazzPerformers();

        require __DIR__ . '/../Views/cms/edit_block.php';
    }

    public function update(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad request';
            return;
        }

        $data = [
            'performer_id' => (int)($_POST['performer_id'] ?? 0),
            'title'        => trim($_POST['title'] ?? ''),
            'subtitle'     => ($_POST['subtitle'] ?? null),
            'body'         => ($_POST['body'] ?? null),
            'url'          => ($_POST['url'] ?? null),
            'image_path'   => ($_POST['image_path'] ?? null),
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
            'is_active'    => (int)($_POST['is_active'] ?? 1),
        ];

        $this->cmsRepo->update($id, $data);

        header('Location: /cms/jazz/home');
        exit;
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->cmsRepo->delete($id);
        }

        header('Location: /cms/jazz/home');
        exit;
    }
}