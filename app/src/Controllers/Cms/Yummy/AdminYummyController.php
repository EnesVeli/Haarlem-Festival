<?php

namespace App\Controllers\Cms\Yummy;

use App\Controllers\Cms\BaseCmsController;
use App\Services\Yummy\YummyCmsService;
use Exception;

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

    public function editHome(){
        $this->requireAdmin();

        //print_r($_FILES);

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle']) || !isset($_FILES['topper_image'])) throw new Exception("");          

            $this->service->editHome($_POST['title'], $_POST['subtitle'], $_FILES['topper_image']['name'], $_FILES['topper_image']['tmp_name']);
        }
        catch(Exception $ex){
            
        }

        header('location: /cms/yummy/');
    }
}