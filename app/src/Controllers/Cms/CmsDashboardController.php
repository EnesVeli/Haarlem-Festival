<?php

namespace App\Controllers\Cms;

use App\Services\CmsDashboardService;
use App\ViewModels\CmsDashboardViewModel;

class CmsDashboardController extends BaseCmsController
{
    private CmsDashboardService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new CmsDashboardService();
    }

    public function index(): void
    {
        $data = $this->service->getDashboardData();
        $vm = new CmsDashboardViewModel($data['user'], $data['sections']);

        require __DIR__ . '/../../Views/cms/dashboard.php';
    }
}