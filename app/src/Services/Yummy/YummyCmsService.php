<?php

namespace App\Services\Yummy;

use App\Models\Exceptions\DBAccessException;
use App\Repositories\YummyCmsRepository;
use App\Repositories\YummyRestaurantsRepository;
use App\ViewModels\Yummy\Cms\YummyHomeViewModel;
use App\ViewModels\Yummy\Cms\YummyListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantsViewModel;
use App\ViewModels\Yummy\Cms\YummyTopper;
use RoundingMode;
use Uri\InvalidUriException;

class YummyCmsService {
    private YummyCmsRepository $cms_rep;
    private YummyRestaurantsRepository $restaurant_rep;

    public function __construct(){
        $this->cms_rep = new YummyCmsRepository();
        $this->restaurant_rep = new YummyRestaurantsRepository();
    }

    public function getHomeViewModel() : YummyHomeViewModel{
        $view_model = new YummyHomeViewModel();

        $view_model->topper = new YummyTopper();
        $view_model->topper->title = "Yummy CMS - Home";
        $view_model->topper->subtitle = "Manage yummy home page.";
        $view_model->topper->button_text = "View page";
        $view_model->topper->button_link = '/yummy';
        $view_model->topper->active_tab = 0;

        $home_data = $this->cms_rep->getHomeData();

        if($home_data == null) throw new DBAccessException();

        $view_model->home_title = $home_data['home_title'];
        $view_model->home_subtitle = $home_data['home_subtitle'];
        $view_model->topper_path = $home_data['home_image'];

        return $view_model;
    }

    public function editHome(string $title, string $subtitle, ?string $image){
        $this->cms_rep->updateHomeData($title, $subtitle, $image);
    }

    public function getListViewModel() : YummyListViewModel{
        $view_model = new YummyListViewModel();

        $view_model->topper = new YummyTopper();
        $view_model->topper->title = "Yummy CMS - List";
        $view_model->topper->subtitle = "Manage yummy restaurant list page.";
        $view_model->topper->button_text = "View page";
        $view_model->topper->button_link = '/yummy/list';
        $view_model->topper->active_tab = 1;

        $home_data = $this->cms_rep->getListData();

        if($home_data == null) throw new DBAccessException();

        $view_model->list_title = $home_data['list_title'];
        $view_model->list_subtitle = $home_data['list_subtitle'];
        $view_model->list_image = $home_data['list_image'];

        return $view_model;
    }

    public function editList(string $title, string $subtitle, ?string $image){
        $this->cms_rep->updateListData($title, $subtitle, $image);
    }

    public function getRestaurantViewModel(int $sort, int $order, int $page) : YummyRestaurantsViewModel {
        // Check parameters
        if($sort < 0 || $sort > 5 || $page < 0) throw new InvalidUriException("Invalid uri parameters.");

        $view_model = new YummyRestaurantsViewModel();

        // Load restaurants
        $list = $this->restaurant_rep->getRestaurantListCms($sort, $order, $page);

        if($list == null) throw new DBAccessException("Could not load restaurant list from db.");
        $view_model->restaurants = $list;

        // Put params into view model
        $view_model->cur_page = $page;

        $count = $this->restaurant_rep->countAllRestaurants();
        if($count == null) throw new DBAccessException("Could not get total restaurant count from db.");
        $view_model->page_number = round($count / YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE_CMS, 0, RoundingMode::AwayFromZero);

        $view_model->sort_field = $sort;
        $view_model->sort_order = $order;

        // Setup topper
        $view_model->topper = new YummyTopper();
        $view_model->topper->title = "Yummy CMS - Restaurants";
        $view_model->topper->subtitle = "Manage yummy restaurants.";
        $view_model->topper->button_text = "View restaurants";
        $view_model->topper->button_link = '/yummy/list';
        $view_model->topper->active_tab = 2;

        return $view_model;
    }
}