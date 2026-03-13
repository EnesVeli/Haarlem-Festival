<?php

namespace App\Services;

use App\Repositories\RestaurantSortingOption;
use App\Repositories\YummyFoodTypeRepository;
use App\Repositories\YummyGuidesRepository;
use App\Repositories\YummyRestaurantsRepository;
use App\ViewModels\YummyHomeViewModel;
use App\ViewModels\YummyListViewModel;
use Exception;
use RoundingMode;

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

    public function getHomeViewModel() : YummyHomeViewModel {
        $view_model = new YummyHomeViewModel();

        $view_model->restaurants = $this->restaurant_repository->getTopActiveRestaurants();
        $view_model->guides = $this->guide_repository->getTopActiveGuides();

        return $view_model;
    }

    public function getListViewModel(?string $place_type, ?string $meal_type, ?string $food_type, ?string $cuisine_type, ?int $sorting, ?int $page) : YummyListViewModel {
        $view_model = new YummyListViewModel();

        // Get Filtering and Sorting
        $view_model->current_place_types = isset($place_type) ? explode(',', $place_type) : [];
        $view_model->current_meal_types = isset($meal_type) ? explode(',', $meal_type) : [];
        $view_model->current_food_types = isset($food_type) ? explode(',', $food_type) : [];
        $view_model->current_cuisine_types = isset($cuisine_type) ? explode(',', $cuisine_type) : [];

        $view_model->sorting = $sorting ?? 0;

        $view_model->current_page = $page ?? 0;

        // Load restaurants from db
        $out = $this->restaurant_repository->getFilteredRestaurants($view_model->getAllCurrentTypes(), $view_model->current_page, RestaurantSortingOption::from($view_model->sorting));

        $view_model->restaurants = $out[1];
        $view_model->total_found_restaurants_number = $out[0];

        // Page calculations
        $page_count = round($out[0] / YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE, 0, RoundingMode::AwayFromZero);

        $offset = 0; // Left offset of pages button
        $limit = 0; // Right offset of pages button

        if($page < abs($page - $page_count + 1)){ // If current page is closer to first page than last, start from offset
            for (; $offset < 3; $offset++) { 
                if($page - $offset <= 0) break;
            }

            for (; $limit < 7 - $offset; $limit++) { 
                if($page + $limit >= $page_count) break;
            }

        }  
        else{ // Otherwise from limit
            for (; $limit < 4; $limit++) { 
                if($page + $limit >= $page_count) break;
            }

            for (; $offset < 7 - $limit; $offset++) { 
                if($page - $offset <= 0) break;
            }                       
        } 

        $view_model->total_pages_number = $page_count;
        $view_model->page_offset = $offset;
        $view_model->page_limit = $limit;

        // Load types form db
        $all_types = $this->type_repository->getAllTypes() ?? [];  
        
        for($i = 0; $i < count($all_types); $i++){ 
            switch($all_types[$i]->category){
                case 0:
                    $view_model->all_place_types[] = $all_types[$i];
                    break;
                case 1:
                    $view_model->all_meal_types[] = $all_types[$i];
                    break;
                case 2:
                    $view_model->all_food_types[] = $all_types[$i];
                    break;
                case 3:
                    $view_model->all_cuisine_types[] = $all_types[$i];
                    break;
            }
        } 

        return $view_model;
    }
}