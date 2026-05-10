<?php

namespace App\Controllers\Cms\Orders;

use App\Controllers\Cms\BaseCmsController;
use App\Framework\Session;
use App\Models\Exceptions\QueryExecutionException;
use App\Services\OrderCmsService;
use App\ViewModels\CmsOrderListViewModel;
use Exception;

class OrderCmsController extends BaseCmsController
{
    public static array $ALLOWED_SORTING = ['date'];
    public static $NUMBER_OF_ORDERS_PER_PAGE = 24;

    private OrderCmsService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = OrderCmsService::getInstance();
    }

    public function index(){
        $this->requireAdmin();

        try{
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
        }
        catch(Exception $ex){
            Session::setTempError("Something went wrong. Try again later." . $ex->getMessage());
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

    private function getSort() : string {
        if(!isset($_GET['page']) || !in_array($_GET['page'], self::$ALLOWED_SORTING)) return 'date';

        return $_GET['page'];
    }

    private function getOrder() : int {
        if(!isset($_GET['order']) || !filter_var($_GET['order'], FILTER_VALIDATE_INT)) return 0;

        if($_GET['order'] === 1) return 1;

        return 0;
    }
}