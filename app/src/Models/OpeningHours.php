<?php

namespace App\Models;

class OpeningHours{
    public int $id;
    public int $restaurant_id;
    public string $monday;
    public string $tuesday;
    public string $wednesday;
    public string $thursday;
    public string $friday;
    public string $saturday;
    public string $sunday;
}