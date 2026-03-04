<?php

namespace App\Services;

use App\Repositories\YummyGuidesRepository;
use App\Repositories\YummyRestaurantsRepository;
use Exception;

class YummyService
{
    private YummyGuidesRepository $guide_repository;
    private YummyRestaurantsRepository $restaurant_repository;

    public function __construct()
    {
        $this->guide_repository = new YummyGuidesRepository();
        $this->restaurant_repository = new YummyRestaurantsRepository();
    }

    public function getActiveGuides() : ?array {
        return $this->guide_repository->getAllActiveGuides();
    }

    public function getActiveRestaurants() : ?array {
        return $this->restaurant_repository->getTopActiveRestaurants();
    }
}