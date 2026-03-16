<?php
namespace App\Controllers;

use App\Services\StoriesService;

class CmsStoriesController extends BaseController
{
    private StoriesService $service;

    public function __construct(StoriesService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $events = $this->service->getAllEvents();
        $this->render('cms/stories/index', ['events' => $events]);
    }

    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $event = $id ? $this->service->getEventById($id) : null;

        // When no id is passed we show a blank create form
        $this->render('cms/stories/form', ['event' => $event]);
    }

    public function save(): void
    {
        // CSRF check
        if (($_POST['csrf_token'] ?? '') !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        $data = [
            'name'              => $_POST['name'] ?? '',
            'slug'              => $_POST['slug'] ?? '',
            'description'       => $_POST['description'] ?? '',
            'language'          => $_POST['language'] ?? 'EN',
            'age_group'         => $_POST['age_group'] ?? 'All Ages',
            'story_type'        => $_POST['story_type'] ?? '',
            'is_pay_as_you_like'=> isset($_POST['is_pay_as_you_like']) ? 1 : 0,
            'start_time'        => $_POST['start_time'] ?? '',
            'end_time'          => $_POST['end_time'] ?? '',
            'max_tickets'       => (int) ($_POST['max_tickets'] ?? 0),
            'performer_name'    => $_POST['performer_name'] ?? null,
            'performer_bio'     => $_POST['performer_bio'] ?? null,
            'venue_id'          => (int) ($_POST['venue_id'] ?? 1),
            'image_path'        => $_POST['existing_image'] ?? '',
        ];

        // Handle image upload
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

        if (!empty($_POST['event_id'])) {
            $this->service->updateEvent((int) $_POST['event_id'], $data);
        } else {
            $this->service->createEvent($data);
        }

        header('Location: /cms/stories');
        exit;
    }

    public function delete(): void
    {
        // CSRF check
        if (($_POST['csrf_token'] ?? '') !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        if (isset($_POST['id'])) {
            $this->service->deleteEvent((int) $_POST['id']);
        }

        header('Location: /cms/stories');
        exit;
    }
}