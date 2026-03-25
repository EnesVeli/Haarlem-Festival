<?php

namespace App\Models;

class Dish{
    public int $dish_id;
    public int $restaurant_id;
    public string $name;
    public string $text;
    public string $image_path;
}