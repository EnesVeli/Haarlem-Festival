<?php

namespace App\ViewModels;

use App\Models\Restaurant;

class YummyRestaurantViewModel {
    public Restaurant $restaurant;
    public array $tags;
    public array $images;
    public array $dishes;
}