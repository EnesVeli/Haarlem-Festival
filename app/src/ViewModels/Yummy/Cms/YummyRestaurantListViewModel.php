<?php

namespace App\ViewModels\Yummy\Cms;

class YummyRestaurantListViewModel{
    public YummyTopper $topper;
    public array $restaurants;

    public int $cur_page;
    public int $page_number;
    public int $page_offset;
    public int $page_limit;

    public int $sort_field;
    public int $sort_order;
}