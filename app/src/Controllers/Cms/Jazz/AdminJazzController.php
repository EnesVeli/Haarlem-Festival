<?php

namespace App\Controllers\Cms\Jazz;

use App\Controllers\Cms\BaseCmsController;
use App\Interfaces\Services\IJazzCmsService;
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
    private IJazzCmsService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = JazzCmsService::getInstance();
    }

    // dashboard

    public function index(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new JazzDashboardCmsViewModel($data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/dashboard.php';
    }

    // hero

    public function hero(): void
    {
        $data = $this->service->getHeroPageData();
        $vm = new JazzHeroCmsViewModel($data['hero'] ?? null, $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/hero.php';
    }

    public function updateHero(): void
    {
        $this->service->updateHero(
            $_POST,
            $_FILES['hero_image'] ?? null
        );

        header('Location: /cms/jazz/hero');
        exit;
    }

    // intro

    public function intro(): void
    {
        $data = $this->service->getIntroPageData();
        $vm = new JazzIntroCmsViewModel($data['intro'] ?? null, $data['user']);

        require __DIR__ . '/../../../Views/cms/jazz/intro.php';
    }

    public function updateIntro(): void
    {
        $this->service->updateIntro($_POST);

        header('Location: /cms/jazz/intro');
        exit;
    }

    // experiences

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

        if ($id <= 0) {
            header('Location: /cms/jazz/experiences');
            exit;
        }

        $data = $this->service->getExperienceByIdData($id);
        $vm = new JazzExperiencesCmsViewModel([], $data['user'], $data['experience'] ?? null);

        require __DIR__ . '/../../../Views/cms/jazz/experiences/edit.php';
    }

    public function storeExperience(): void
    {
        $this->service->storeExperience(
            $_POST,
            $_FILES['experience_image'] ?? null
        );

        header('Location: /cms/jazz/experiences');
        exit;
    }

    public function updateExperience(): void
    {
        $this->service->updateExperience(
            $_POST,
            $_FILES['experience_image'] ?? null
        );

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

    // performers

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
    
        if ($id <= 0) {
            header('Location: /cms/jazz/performers');
            exit;
        }
    
        $data = $this->service->getPerformerByIdData($id);
        $vm = new JazzPerformersCmsViewModel(
            [],
            $data['user'],
            $data['performer'] ?? null,
            $data['highlights'] ?? [],
            $data['tracks'] ?? []
        );
    
        require __DIR__ . '/../../../Views/cms/jazz/performers/edit.php';
    }

    public function storePerformer(): void
    {
        $this->service->storePerformer(
            $_POST,
            $_FILES['performer_hero_image'] ?? null,
            $_FILES['performer_image'] ?? null
        );

        header('Location: /cms/jazz/performers');
        exit;
    }

    public function updatePerformer(): void
    {
        $this->service->updatePerformer(
            $_POST,
            $_FILES['performer_hero_image'] ?? null,
            $_FILES['performer_image'] ?? null
        );

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

    // recommendations

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

        if ($id <= 0) {
            header('Location: /cms/jazz/recommendations');
            exit;
        }

        $data = $this->service->getRecommendationByIdData($id);
        $vm = new JazzRecommendationsCmsViewModel([], $data['user'], $data['recommendation'] ?? null);

        require __DIR__ . '/../../../Views/cms/jazz/recommendations/edit.php';
    }

    public function storeRecommendation(): void
    {
        $this->service->storeRecommendation(
            $_POST,
            $_FILES['recommendation_image'] ?? null
        );

        header('Location: /cms/jazz/recommendations');
        exit;
    }

    public function updateRecommendation(): void
    {
        $this->service->updateRecommendation(
            $_POST,
            $_FILES['recommendation_image'] ?? null
        );

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

    // locations

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

        if ($id <= 0) {
            header('Location: /cms/jazz/locations');
            exit;
        }

        $data = $this->service->getLocationByIdData($id);
        $vm = new JazzLocationsCmsViewModel([], $data['user'], $data['location'] ?? null);

        require __DIR__ . '/../../../Views/cms/jazz/locations/edit.php';
    }

    public function storeLocation(): void
    {
        $this->service->storeLocation($_POST);

        header('Location: /cms/jazz/locations');
        exit;
    }

    public function updateLocation(): void
    {
        $this->service->updateLocation($_POST);

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