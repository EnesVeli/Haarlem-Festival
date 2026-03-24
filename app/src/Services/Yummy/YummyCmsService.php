<?php

namespace App\Services\Yummy;

use App\Models\Exceptions\DBAccessException;
use App\Repositories\YummyCmsRepository;
use App\ViewModels\Yummy\Cms\YummyHomeViewModel;
use App\ViewModels\Yummy\Cms\YummyTopper;

class YummyCmsService{
    private YummyCmsRepository $cms_rep;

    public function __construct(){
        $this->cms_rep = new YummyCmsRepository();
    }

    public function getHomeViewModel() : YummyHomeViewModel{
        $view_model = new YummyHomeViewModel();

        $view_model->topper = new YummyTopper();
        $view_model->topper->title = "Yummy CMS - Home";
        $view_model->topper->subtitle = "Manage yummy home page.";
        $view_model->topper->button_text = "View page";
        $view_model->topper->button_link = '/yummy';
        $view_model->topper->active_tab = 0;

        $home_data = $this->cms_rep->getHomeData();

        if($home_data == null) throw new DBAccessException();

        $view_model->home_title = $home_data['home_title'];
        $view_model->home_subtitle = $home_data['home_subtitle'];
        $view_model->topper_path = $home_data['home_image'];

        return $view_model;
    }

    public function editHome(string $title, string $subtitle, string $temp_file_name, string $temp_file_tmp){
        $file_name = bin2hex(openssl_random_pseudo_bytes(32)) . '.' . pathinfo($temp_file_name, PATHINFO_EXTENSION);
        $path = __DIR__ . '/../../../../public/assets/uploads/yummy/topper/' . $file_name;

        move_uploaded_file($temp_file_tmp, $path);
    }
}