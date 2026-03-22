<?php

namespace App\ViewModels\Yummy;

use App\Models\Restaurant;

class YummyRestaurantViewModel {
    public Restaurant $restaurant;
    public array $tags;
    public array $images;
    public array $dishes;
}