<?php

namespace App\ViewModels\Yummy;

use App\Models\Restaurant;

class YummyBookViewModel{
    public Restaurant $restaurant;
    public array $time_slots;
    public array $dates;
}