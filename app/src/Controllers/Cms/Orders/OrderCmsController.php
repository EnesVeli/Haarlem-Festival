<?php

namespace App\Controllers\Cms\Orders;

use App\Controllers\Cms\BaseCmsController;
use App\Framework\Session;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\QueryExecutionException;
use App\Services\OrderCmsService;
use App\ViewModels\CmsOrderListViewModel;
use App\ViewModels\OrderCmsExportParam;
use App\ViewModels\ViewOrderCmsViewModel;
use Exception;

class OrderCmsController extends BaseCmsController
{
    public static array $ALLOWED_SORTING = [0, 1, 2];
    public static $NUMBER_OF_ORDERS_PER_PAGE = 12;

    private OrderCmsService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = OrderCmsService::getInstance();
    }

    public function index(){
        $this->requireAdmin();

        $error_message = Session::popTempError();

        try{
            //throw new Exception();
            // Get order number
            $total_order_number = $this->service->getTotalOrderNumberForCms();
            if($total_order_number === false) throw new QueryExecutionException('Failed to get order number for order cms.');

            // Calc number of pages
            $total_page_number = ceil($total_order_number / self::$NUMBER_OF_ORDERS_PER_PAGE);

            // Get url values
            $page = $this->getPage($total_page_number);
            $sort = $this->getSort();
            $order = $this->getOrder();

            // Get orders
            $orders = $this->service->getOrdersSortedForCms(self::$NUMBER_OF_ORDERS_PER_PAGE, $page, $sort, $order);
            if($orders === false) throw new QueryExecutionException('Failed to get orders for order cms.');

            $view_model = new CmsOrderListViewModel();
            $view_model->orders = $orders;
            $view_model->sorting = $sort;
            $view_model->sorting_order = $order;
            $view_model->current_page = $page;
            $view_model->total_page_number = $total_page_number;

            $view_model->calcOffsets();
        }
        catch(Exception $ex){
            $error_message = "Something went wrong. Try again later.";
        }

        require __DIR__ . '/../../../Views/cms/orders/index.php';
    }

    private function getPage(int $total_pages) : int {
        if(!isset($_GET['page']) || !filter_var($_GET['page'], FILTER_VALIDATE_INT)) return 1;

        $page = $_GET['page'];

        if($page < 1) return 1;

        if($page > $total_pages) return $total_pages;

        return $page;    
    }

    private function getSort() : int {
        if(!isset($_GET['sort']) || !filter_var($_GET['sort'], FILTER_VALIDATE_INT) || !in_array($_GET['sort'], self::$ALLOWED_SORTING)) return 0;

        return $_GET['sort'];
    }

    private function getOrder() : int {
        if(!isset($_GET['order']) || !filter_var($_GET['order'], FILTER_VALIDATE_INT)) return 0;

        if($_GET['order'] == 1) return 1;

        return 0;
    }

    public function view(){
        $this->requireAdmin();

        try{
            if(!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) throw new EmptyPostException('Order id is not set.');

            $order = $this->service->getOrderForView($_GET['id']);
            if($order === null) throw new QueryExecutionException('Failed to get order for view cms.');

            $view_model = new ViewOrderCmsViewModel();
            $view_model->order = $order;

            require __DIR__ . '/../../../Views/cms/orders/order-view.php';
            exit;
        }
        catch(Exception $ex){
            Session::setTempError('Something went wrong. Try again later.');
        }

        header('Location: /cms/order');
    }

    public function exportPage(){
        $this->requireAdmin();

        $error_message = Session::popTempError();

        require __DIR__ . '/../../../Views/cms/orders/export.php';
    }

    public function export(){
        $this->requireAdmin();

        try{
            if(count($_POST) < 1) throw new EmptyPostException();

            $export_param = new OrderCmsExportParam();
            $export_param->user_id =     $_POST['user_id'] ?? false;
            $export_param->user_email =  $_POST['user_email'] ?? false;
            $export_param->user_name =   $_POST['user_name'] ?? false;
            $export_param->order_id =    $_POST['order_id'] ?? false;
            $export_param->order_date =  $_POST['date'] ?? false;
            $export_param->total_price = $_POST['total_price'] ?? false;
            $export_param->status =      $_POST['status'] ?? false;
            $export_param->status_1 =    $_POST['status_1'] ?? false;
            $export_param->status_2 =    $_POST['status_2'] ?? false;
            $export_param->status_3 =    $_POST['status_3'] ?? false;

            $this->service->exportOrderToCsv($export_param);
        }
        catch(Exception $ex){
            Session::setTempError('Something went wrong. Try again later.');
        }

        //header('Location: /cms/order/export');
    }
}