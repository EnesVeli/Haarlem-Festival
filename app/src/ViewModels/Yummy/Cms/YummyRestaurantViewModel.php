<?php

namespace App\ViewModels\Yummy\Cms;

use App\Models\OpeningHours;
use App\Models\Restaurant;

class YummyRestaurantViewModel{
    public YummyTopper $topper;
    public Restaurant $res;
    public OpeningHours $hours;
    public ?array $images;
}