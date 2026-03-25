<?php

namespace App\ViewModels\Yummy\Cms;

class YummyRestaurantsViewModel{
    public YummyTopper $topper;
    public array $restaurants;

    public int $cur_page;
    public int $page_number;

    public int $sort_field;
    public int $sort_order;
}