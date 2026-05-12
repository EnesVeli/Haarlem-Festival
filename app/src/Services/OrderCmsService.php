<?php

namespace App\Services;

use App\Repositories\HistoryCmsRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\JazzRepository;
use App\Repositories\OrderRepository;
use App\Repositories\StoriesRepository;
use App\Repositories\UserRepository;
use App\Repositories\YummyRestaurantsRepository;

class OrderCmsService {
    private static ?OrderCmsService $_instance = null;

    public static function getInstance() : OrderCmsService {
        if(self::$_instance === null) self::$_instance = new OrderCmsService(UserRepository::getInstance(), OrderRepository::getInstance(),
            HistoryCmsRepository::getInstance(), YummyRestaurantsRepository::getInstance(), StoriesRepository::getInstance(), HistoryRepository::getInstance(),
            JazzRepository::getInstance());

        return self::$_instance;
    }

    private UserRepository $user_rep;
    private OrderRepository $order_rep;
    private HistoryCmsRepository $history_cms_rep;
    private YummyRestaurantsRepository $restaurant_rep;
    private StoriesRepository $story_rep;
    private HistoryRepository $history_rep;
    private JazzRepository $jazz_rep;

    private function __construct(UserRepository $user_rep, OrderRepository $order_rep, HistoryCmsRepository $history_cms_rep,
        YummyRestaurantsRepository $restaurant_rep, StoriesRepository $story_rep, HistoryRepository $history_rep, JazzRepository $jazz_rep)
    {
        $this->user_rep = $user_rep;
        $this->order_rep = $order_rep;
        $this->history_cms_rep = $history_cms_rep;
        $this->restaurant_rep = $restaurant_rep;
        $this->story_rep = $story_rep;
        $this->history_rep = $history_rep;
        $this->jazz_rep = $jazz_rep;
    }

    public function getTotalOrderNumberForCms() : int|bool {
        return $this->order_rep->getTotalOrderNumberForCms();
    }

    public function getOrdersSortedForCms(int $orders_per_page, int $page, int $sort, int $sort_order) : array|null|bool {
        return $this->order_rep->getOrdersSortedForCms($orders_per_page, $page, $sort, $sort_order);
    }
}