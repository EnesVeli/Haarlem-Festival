<?php

namespace App\Controllers\Cms\User;

use App\Controllers\Cms\BaseCmsController;
use App\Enums\UserRole;
use App\Framework\Session;
use App\Models\Exceptions\DBDataNotFoundException;
use App\Models\Exceptions\EmailAlreadyRegisteredException;
use App\Models\Exceptions\EmptyPasswordException;
use App\Models\Exceptions\InappropriatePasswordLengthException;
use App\Models\Exceptions\IncorrectEmailException;
use App\Models\User;
use App\Services\UserCmsService;
use App\ViewModels\User\UserListViewModel;
use App\ViewModels\User\UserTopper;
use App\ViewModels\User\ViewUserViewModel;
use Exception;
use Uri\InvalidUriException;

class UserCmsController extends BaseCmsController
{
    private UserCmsService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = UserCmsService::getInstance();
    }

    public function list(){
        $this->requireAdmin();

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();

        try{
            // Checking arguments
            $sort = $_GET['sort'] ?? 0;
            $order = $_GET['order'] ?? 0;
            $page = $_GET['page'] ?? 0;

            if(!is_numeric($sort) || !is_numeric($sort) || !is_numeric($sort)) throw new InvalidUriException('Invalid uri parameters.');

            // Setting up view model
            $view_model = new UserListViewModel();

            $view_model->sorting = $sort;
            $view_model->sort_order = $order;
            $view_model->cur_page = $page;

            // Get user list
            $view_model->users = $this->service->getUsersList($sort, $order, $page);          

            // Pagination calculations
            $total_user_number = $this->service->countUsers();

            $paginataion = $this->service->calcPagination($page, $total_user_number);

            $view_model->page_offset = $paginataion['offset'];
            $view_model->page_limit = $paginataion['limit'];
            $view_model->total_page_number = $paginataion['page_count'];

            // Topper setup
            $view_model->topper = new UserTopper();
            $view_model->topper->title = "User CMS - List";
            $view_model->topper->subtitle = "Manage festival users.";
            $view_model->topper->active_tab = 0;
        }
        catch(Exception $ex){
            $error_message = "Something went wrong, try again later.";
        }

        require __DIR__ . '/../../../Views/cms/user/list.php';
    }

    public function view(){
        $this->requireAdmin();

        $error_message = Session::popTempError();
        $success_message = Session::popTempSuccess();

        try{
            if(!isset($_GET['id']) || !is_numeric($_GET['id'])) throw new InvalidUriException();

            $view_model = new ViewUserViewModel();

            // Get user
            $u = $this->service->getByUserId($_GET['id']);
            if($u === null) throw new DBDataNotFoundException('User with given id was not found.');
            $view_model->user = $u;

            // Setup topper
            $view_model->topper = new UserTopper();
            $view_model->topper->title = "User CMS - View - " . $u->name;
            $view_model->topper->subtitle = "Manage festival users.";
            $view_model->topper->active_tab = -1;

            // Setup roles
            $view_model->roles = [];

            array_push($view_model->roles, UserRole::Customer->value);
            array_push($view_model->roles, UserRole::Admin->value);
            array_push($view_model->roles, UserRole::Employee->value);

            require __DIR__ . '/../../../Views/cms/user/view.php';
            exit;
        }
        catch(Exception $ex){
            $error_message = "Something went wrong, try again later.";
        }

        header('Location: /cms/user');
    }

    public function edit(){
        //print_r($_FILES); exit;

        $this->requireAdmin();

        try{
            // Get data from post
            $edit = new User();

            $edit->user_id = $_POST['user_id'];
            $edit->name = $_POST['name'];
            $edit->email = $_POST['email'];
            $edit->password = $_POST['password'];
            $edit->active = $_POST['active'];
            $edit->role = UserRole::from($_POST['role']);

            // Edit user
            $this->service->editUser($edit, empty($_FILES['profile_pic']['name']) ? null : $_FILES['profile_pic']);

            Session::setTempSuccess('User was successfully edited.');
        }
        catch(EmailAlreadyRegisteredException $ex){
            Session::setTempError("This email is already registered.");
        }
        catch(EmptyPasswordException $ex){
            Session::setTempError("The password must not be empty");
        }
        catch(InappropriatePasswordLengthException $ex){
            Session::setTempError("Your password must be at least 8 and maximum 255 characters.");
        }
        catch(IncorrectEmailException $ex){
           Session::setTempError("Enter a valid email address.");
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong, try again later." . $ex->getMessage());
        }

        if(isset($_POST['user_id'])) header('Location: /cms/user/view?id=' . $_POST['user_id']);
        else header('Location: /cms/user');
    }
}