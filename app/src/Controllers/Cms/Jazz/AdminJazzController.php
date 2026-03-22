<?php

namespace App\Controllers\Cms\Jazz;

use App\Controllers\Cms\BaseCmsController;
use App\Services\Jazz\JazzCmsService;

use App\ViewModels\Jazz\JazzCmsViewModels\JazzDashboardCmsViewModel;
use App\ViewModels\Jazz\JazzCmsViewModels\JazzHeroCmsViewModel;
use App\ViewModels\Jazz\JazzCmsViewModels\JazzIntroCmsViewModel;
use App\ViewModels\Jazz\JazzCmsViewModels\JazzExperiencesCmsViewModel;
use App\ViewModels\Jazz\JazzCmsViewModels\JazzPerformersCmsViewModel;
use App\ViewModels\Jazz\JazzCmsViewModels\JazzRecommendationsCmsViewModel;
use App\ViewModels\Jazz\JazzCmsViewModels\JazzLocationsCmsViewModel;

class AdminJazzController extends BaseCmsController
{
    private JazzCmsService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new JazzCmsService();
    }

    private function getUploadRoot(): string
    {
        return __DIR__ . '/../../../../public';
    }

  //dashboard
    public function index(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new JazzDashboardCmsViewModel($data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/dashboard.php';
    }

    //intro
    public function hero(): void
    {
        $data = $this->service->getHeroPageData();
        $vm = new JazzHeroCmsViewModel($data['hero'] ?? [], $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/hero.php';
    }

    public function updateHero(): void
    {
        $uploadRoot = $this->getUploadRoot();

        $data = [
            'id' => $_POST['id'] ?? 0,
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'is_active' => $_POST['is_active'] ?? 0
        ];

        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['tmp_name']) {
            $filename = time() . '_' . basename($_FILES['hero_image']['name']);
            $path = '/assets/uploads/jazz/hero/' . $filename;
            $fullPath = $uploadRoot . $path;

            $uploadDir = dirname($fullPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['hero_image']['tmp_name'], $fullPath);
            $data['image_path'] = $path;
        }

        $this->service->updateHero($data);

        header('Location: /cms/jazz/hero');
        exit;
    }

  //intro

    public function intro(): void
    {
        $data = $this->service->getIntroPageData();
        $vm = new JazzIntroCmsViewModel($data['intro'] ?? [], $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/intro.php';
    }

    public function updateIntro(): void
    {
        $data = [
            'id' => $_POST['id'] ?? 0,
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        $this->service->updateIntro($data);

        header('Location: /cms/jazz/intro');
        exit;
    }

   //experiences

    public function experiences(): void
    {
        $data = $this->service->getExperiencesPageData();
        $vm = new JazzExperiencesCmsViewModel($data['experiences'], $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/experiences/list.php';
    }

    public function createExperience(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new JazzExperiencesCmsViewModel([], $data['user']);
    
        require __DIR__ . '/../../../Views/cms/jazz/experiences/create.php';
    }
    
    public function editExperience(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->service->getExperienceByIdData($id);
    
        $vm = new JazzExperiencesCmsViewModel([], $data['user'], $data['experience'] ?? []);
    
        require __DIR__ . '/../../../Views/cms/jazz/experiences/edit.php';
    }

    public function storeExperience(): void
    {
        $uploadRoot = $this->getUploadRoot();

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 0
        ];

        if (isset($_FILES['experience_image']) && $_FILES['experience_image']['tmp_name']) {
            $filename = time() . '_' . basename($_FILES['experience_image']['name']);
            $path = '/assets/uploads/jazz/experiences/' . $filename;
            $fullPath = $uploadRoot . $path;

            $uploadDir = dirname($fullPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['experience_image']['tmp_name'], $fullPath);
            $data['image_path'] = $path;
        }

        $this->service->storeExperience($data);

        header('Location: /cms/jazz/experiences');
        exit;
    }

    public function updateExperience(): void
    {
        $uploadRoot = $this->getUploadRoot();

        $data = [
            'id' => $_POST['id'] ?? 0,
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 0
        ];

        if (isset($_FILES['experience_image']) && $_FILES['experience_image']['tmp_name']) {
            $filename = time() . '_' . basename($_FILES['experience_image']['name']);
            $path = '/assets/uploads/jazz/experiences/' . $filename;
            $fullPath = $uploadRoot . $path;

            $uploadDir = dirname($fullPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['experience_image']['tmp_name'], $fullPath);
            $data['image_path'] = $path;
        }

        $this->service->updateExperience($data);

        header('Location: /cms/jazz/experiences');
        exit;
    }

    public function deleteExperience(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->service->deleteExperience($id);
        }

        header('Location: /cms/jazz/experiences');
        exit;
    }

   //performers

    public function performers(): void
    {
        $data = $this->service->getPerformersPageData();
        $vm = new JazzPerformersCmsViewModel($data['performers'], $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/performers/list.php';
    }

    public function createPerformer(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new JazzPerformersCmsViewModel([], $data['user']);
    
        require __DIR__ . '/../../../Views/cms/jazz/performers/create.php';
    }
    
    public function editPerformer(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->service->getPerformerByIdData($id);
    
        $vm = new JazzPerformersCmsViewModel([], $data['user'], $data['performer'] ?? []);
    
        require __DIR__ . '/../../../Views/cms/jazz/performers/edit.php';
    }

    public function storePerformer(): void
{
    $uploadRoot = $this->getUploadRoot();

    $data = [
        'name' => $_POST['name'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'performance_style' => $_POST['performance_style'] ?? '',
        'event_date_text' => $_POST['event_date_text'] ?? '',
        'event_time_text' => $_POST['event_time_text'] ?? '',
        'venue_name' => $_POST['venue_name'] ?? '',
        'venue_address' => $_POST['venue_address'] ?? '',
        'price_text' => $_POST['price_text'] ?? '',
        'note_text' => $_POST['note_text'] ?? '',
        'audio_url' => $_POST['audio_url'] ?? '',
        'sort_order' => $_POST['sort_order'] ?? 0,
        'is_active' => $_POST['is_active'] ?? 0
    ];

    if (isset($_FILES['performer_hero_image']) && $_FILES['performer_hero_image']['tmp_name']) {
        $filename = time() . '_' . basename($_FILES['performer_hero_image']['name']);
        $path = '/assets/uploads/jazz/performers/' . $filename;
        $fullPath = $uploadRoot . $path;

        $uploadDir = dirname($fullPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['performer_hero_image']['tmp_name'], $fullPath);
        $data['hero_image_path'] = $path;
    }

    if (isset($_FILES['performer_image']) && $_FILES['performer_image']['tmp_name']) {
        $filename = time() . '_' . basename($_FILES['performer_image']['name']);
        $path = '/assets/uploads/jazz/performers/' . $filename;
        $fullPath = $uploadRoot . $path;

        $uploadDir = dirname($fullPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['performer_image']['tmp_name'], $fullPath);
        $data['image_path'] = $path;
    }

    $this->service->storePerformer($data);

    header('Location: /cms/jazz/performers');
    exit;
}

public function updatePerformer(): void
{
    $uploadRoot = $this->getUploadRoot();

    $data = [
        'id' => $_POST['id'] ?? 0,
        'name' => $_POST['name'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'performance_style' => $_POST['performance_style'] ?? '',
        'event_date_text' => $_POST['event_date_text'] ?? '',
        'event_time_text' => $_POST['event_time_text'] ?? '',
        'venue_name' => $_POST['venue_name'] ?? '',
        'venue_address' => $_POST['venue_address'] ?? '',
        'price_text' => $_POST['price_text'] ?? '',
        'note_text' => $_POST['note_text'] ?? '',
        'audio_url' => $_POST['audio_url'] ?? '',
        'sort_order' => $_POST['sort_order'] ?? 0,
        'is_active' => $_POST['is_active'] ?? 0
    ];

    if (isset($_FILES['performer_hero_image']) && $_FILES['performer_hero_image']['tmp_name']) {
        $filename = time() . '_' . basename($_FILES['performer_hero_image']['name']);
        $path = '/assets/uploads/jazz/performers/' . $filename;
        $fullPath = $uploadRoot . $path;

        $uploadDir = dirname($fullPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['performer_hero_image']['tmp_name'], $fullPath);
        $data['hero_image_path'] = $path;
    }

    if (isset($_FILES['performer_image']) && $_FILES['performer_image']['tmp_name']) {
        $filename = time() . '_' . basename($_FILES['performer_image']['name']);
        $path = '/assets/uploads/jazz/performers/' . $filename;
        $fullPath = $uploadRoot . $path;

        $uploadDir = dirname($fullPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['performer_image']['tmp_name'], $fullPath);
        $data['image_path'] = $path;
    }

    $this->service->updatePerformer($data);

    header('Location: /cms/jazz/performers');
    exit;
}
    public function deletePerformer(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->service->deletePerformer($id);
        }

        header('Location: /cms/jazz/performers');
        exit;
    }

  //recommendations

    public function recommendations(): void
    {
        $data = $this->service->getRecommendationsPageData();
        $vm = new JazzRecommendationsCmsViewModel($data['recommendations'], $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/recommendations/list.php';
    }

    public function createRecommendation(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new JazzRecommendationsCmsViewModel([], $data['user']);
    
        require __DIR__ . '/../../../Views/cms/jazz/recommendations/create.php';
    }
    
    public function editRecommendation(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->service->getRecommendationByIdData($id);
    
        $vm = new JazzRecommendationsCmsViewModel([], $data['user'], $data['recommendation'] ?? []);
    
        require __DIR__ . '/../../../Views/cms/jazz/recommendations/edit.php';
    }

    public function storeRecommendation(): void
    {
        $uploadRoot = $this->getUploadRoot();

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'url' => $_POST['url'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 0
        ];

        if (isset($_FILES['recommendation_image']) && $_FILES['recommendation_image']['tmp_name']) {
            $filename = time() . '_' . basename($_FILES['recommendation_image']['name']);
            $path = '/assets/uploads/jazz/recommendations/' . $filename;
            $fullPath = $uploadRoot . $path;

            $uploadDir = dirname($fullPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['recommendation_image']['tmp_name'], $fullPath);
            $data['image_path'] = $path;
        }

        $this->service->storeRecommendation($data);

        header('Location: /cms/jazz/recommendations');
        exit;
    }

    public function updateRecommendation(): void
    {
        $uploadRoot = $this->getUploadRoot();

        $data = [
            'id' => $_POST['id'] ?? 0,
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'url' => $_POST['url'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 0
        ];

        if (isset($_FILES['recommendation_image']) && $_FILES['recommendation_image']['tmp_name']) {
            $filename = time() . '_' . basename($_FILES['recommendation_image']['name']);
            $path = '/assets/uploads/jazz/recommendations/' . $filename;
            $fullPath = $uploadRoot . $path;

            $uploadDir = dirname($fullPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['recommendation_image']['tmp_name'], $fullPath);
            $data['image_path'] = $path;
        }

        $this->service->updateRecommendation($data);

        header('Location: /cms/jazz/recommendations');
        exit;
    }

    public function deleteRecommendation(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->service->deleteRecommendation($id);
        }

        header('Location: /cms/jazz/recommendations');
        exit;
    }

    //locations

    public function locations(): void
    {
        $data = $this->service->getLocationsPageData();
        $vm = new JazzLocationsCmsViewModel($data['locations'], $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/locations/list.php';
    }

    public function createLocation(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new JazzLocationsCmsViewModel([], $data['user']);
    
        require __DIR__ . '/../../../Views/cms/jazz/locations/create.php';
    }
    
    public function editLocation(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->service->getLocationByIdData($id);
    
        $vm = new JazzLocationsCmsViewModel([], $data['user'], $data['location'] ?? []);
    
        require __DIR__ . '/../../../Views/cms/jazz/locations/edit.php';
    }

    public function storeLocation(): void
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'address' => $_POST['address'] ?? '',
            'google_maps_embed_url' => $_POST['google_maps_embed_url'] ?? '',
            'is_active' => $_POST['is_active'] ?? 0
        ];

        $this->service->storeLocation($data);

        header('Location: /cms/jazz/locations');
        exit;
    }

    public function updateLocation(): void
    {
        $data = [
            'id' => $_POST['id'] ?? 0,
            'name' => $_POST['name'] ?? '',
            'address' => $_POST['address'] ?? '',
            'google_maps_embed_url' => $_POST['google_maps_embed_url'] ?? '',
            'is_active' => $_POST['is_active'] ?? 0
        ];

        $this->service->updateLocation($data);

        header('Location: /cms/jazz/locations');
        exit;
    }

    public function deleteLocation(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->service->deleteLocation($id);
        }

        header('Location: /cms/jazz/locations');
        exit;
    }
}