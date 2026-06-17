<?php

namespace App\Controllers\Cms\User;

use App\Controllers\Cms\BaseCmsController;
use App\Framework\Session;
use App\Services\UserCmsService;
use App\ViewModels\User\UserListViewModel;
use App\ViewModels\User\UserTopper;
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
}