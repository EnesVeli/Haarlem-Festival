<?php

namespace App\Controllers\Cms\Yummy;

use App\Controllers\Cms\BaseCmsController;
use App\Services\Yummy\YummyCmsService;

class AdminYummyController extends BaseCmsController {
    private YummyCmsService $service;

    public function __construct(){
        $this->service = new YummyCmsService(); 
    }

    public function index(){
        $this->requireAdmin();

        $view_model = $this->service->getHomeViewModel();

        require __DIR__ . '/../../../Views/cms/yummy/index.php';
    }
}