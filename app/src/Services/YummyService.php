<?php

namespace App\Services;

use App\Repositories\RestaurantSortingOption;
use App\Repositories\YummyFoodTypeRepository;
use App\Repositories\YummyGuidesRepository;
use App\Repositories\YummyRestaurantsRepository;

use Exception;

class YummyService
{
    private YummyGuidesRepository $guide_repository;
    private YummyRestaurantsRepository $restaurant_repository;
    private YummyFoodTypeRepository $type_repository;

    public function __construct()
    {
        $this->guide_repository = new YummyGuidesRepository();
        $this->restaurant_repository = new YummyRestaurantsRepository();
        $this->type_repository = new YummyFoodTypeRepository();
    }

    public function getActiveGuides() : ?array {
        return $this->guide_repository->getAllActiveGuides();
    }

    public function getActiveRestaurants() : ?array {
        return $this->restaurant_repository->getTopActiveRestaurants();
    }

    public function getTypes() : ?array {
        return $this->type_repository->getAllTypes();     
    }

    public function getRestaurantFiltered($place_type, $meal_type, $food_type, $cuisine_type, $sorting, $page) : ?array {
        return $this->restaurant_repository->getFilteredRestaurants(array_merge($place_type, $meal_type, $food_type, $cuisine_type), $page, RestaurantSortingOption::from($sorting));
    }
}