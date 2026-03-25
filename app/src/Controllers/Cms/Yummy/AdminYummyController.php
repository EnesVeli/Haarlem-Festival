<?php

namespace App\Controllers\Cms\Yummy;

use App\Controllers\Cms\BaseCmsController;
use App\Models\Exceptions\EmptyFieldException;
use App\Services\Yummy\YummyCmsService;
use Exception;

class AdminYummyController extends BaseCmsController {
    private YummyCmsService $service;

    public function __construct(){
        $this->service = new YummyCmsService(); 
    }

    public function index(){
        $this->requireAdmin();

        $error_message = $_SESSION['temp_error'] ?? null;
        $_SESSION['temp_error'] = null;

        try{
            $view_model = $this->service->getHomeViewModel();
        }
        catch(Exception $ex){
            if(!isset($error_message)) $error_message = "Something went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/index.php';
    }

    public function editHome(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle'])) throw new EmptyFieldException();          

            if($_FILES['topper_image']['name'] != null){
                // Creating image file
                $file_name = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($_FILES['topper_image']['name'], PATHINFO_EXTENSION);
                $path = __DIR__ . '/../../../../public/assets/uploads/yummy/topper/' . $file_name;

                move_uploaded_file($_FILES['topper_image']['tmp_name'], $path);

                // Sending data to db
                $this->service->editHome($_POST['title'], $_POST['subtitle'], $file_name);
            }
            else{
                $this->service->editHome($_POST['title'], $_POST['subtitle'], null);
            }
        }
        catch(Exception $ex){    
            $_SESSION['temp_error'] = "Something went wrong try again later.";
        }

        header('location: /cms/yummy/');
    }

    public function list(){
        $this->requireAdmin();

        $error_message = $_SESSION['temp_error'] ?? null;
        $_SESSION['temp_error'] = null;

        try{
            $view_model = $this->service->getListViewModel();
        }
        catch(Exception $ex){
            if(!isset($error_message)) $error_message = "Something went wrong try again later.";
        }

        require __DIR__ . '/../../../Views/cms/yummy/list.php';
    }

    public function editList(){
        $this->requireAdmin();

        try{
            if(!isset($_POST['title']) || !isset($_POST['subtitle'])) throw new EmptyFieldException();                    

            if($_FILES['topper_image']['name'] != null){
                // Creating image file
                $file_name = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($_FILES['topper_image']['name'], PATHINFO_EXTENSION);
                $path = __DIR__ . '/../../../../public/assets/uploads/yummy/topper/' . $file_name;

                move_uploaded_file($_FILES['topper_image']['tmp_name'], $path);

                // Sending data to db
                $this->service->editList($_POST['title'], $_POST['subtitle'], $file_name);
            }
            else{
                $this->service->editList($_POST['title'], $_POST['subtitle'], null);
            }    
        }
        catch(Exception $ex){    
            $_SESSION['temp_error'] = "Something went wrong try again later.";
        }

        header('location: /cms/yummy/list');
    }

    public function restaurant(){
        $this->requireAdmin();

        try{
            $view_model = $this->service->getRestaurantViewModel($_GET['sort'] ?? 0, $_GET['order'] ?? 0, $_GET['page'] ?? 0);
        }
        catch(Exception $ex){

        }

        require __DIR__ . '/../../../Views/cms/yummy/restaurant.php';
    }
}