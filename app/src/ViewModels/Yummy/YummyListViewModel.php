<?php

namespace App\ViewModels\Yummy;

class YummyListViewModel
{
    public array $all_place_types = [];
    public array $all_meal_types = [];
    public array $all_food_types = [];
    public array $all_cuisine_types = [];

    public array $current_place_types;
    public array $current_meal_types;
    public array $current_food_types;
    public array $current_cuisine_types;

    public int $sorting;

    public array $restaurants;

    public int $total_found_restaurants_number;

    public int $total_pages_number;
    public int $page_offset;
    public int $page_limit;

    public int $current_page;

    public function __construct()
    {
        
    }

    public function getAllCurrentTypes() : array{
        return array_merge($this->current_place_types, $this->current_meal_types, $this->current_food_types, $this->current_cuisine_types);
    }
}