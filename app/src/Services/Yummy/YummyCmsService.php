<?php

namespace App\Services\Yummy;

use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\DBDataException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\MaxCountExceededException;
use App\Repositories\YummyCmsRepository;
use App\Repositories\YummyRestaurantsRepository;
use App\ViewModels\Yummy\Cms\YummyHomeViewModel;
use App\ViewModels\Yummy\Cms\YummyListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantViewModel;
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

    public function getRestaurantListViewModel(int $sort, int $order, int $page) : YummyRestaurantListViewModel {
        // Check parameters
        if($sort < 0 || $sort > 4 || $page < 0) throw new InvalidUriException("Invalid uri parameters.");

        $view_model = new YummyRestaurantListViewModel();

        // Load restaurants
        $list = $this->restaurant_rep->getRestaurantListCms($sort, $order, $page);

        if($list == null) throw new DBAccessException("Could not load restaurant list from db.");
        $view_model->restaurants = $list;

        // Put params into view model
        $view_model->cur_page = $page;

        $count = $this->restaurant_rep->countAllRestaurants();
        if($count == null) throw new DBAccessException("Could not get total restaurant count from db.");

        $page_count = round($count / YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE_CMS, 0, RoundingMode::AwayFromZero);

        $view_model->page_number = $page_count;

        $view_model->sort_field = $sort;
        $view_model->sort_order = $order;

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

        $view_model->page_offset = $offset;
        $view_model->page_limit = $limit;

        // Setup topper
        $view_model->topper = new YummyTopper();
        $view_model->topper->title = "Yummy CMS - Restaurants";
        $view_model->topper->subtitle = "Manage yummy restaurants.";
        $view_model->topper->button_text = "View restaurants";
        $view_model->topper->button_link = '/yummy/list';
        $view_model->topper->active_tab = 2;

        return $view_model;
    }

    public function getRestaurantViewModel(int $res_id) : YummyRestaurantViewModel {
        $view_model = new YummyRestaurantViewModel();

        $res = $this->restaurant_rep->getRestaurantById($res_id);
        if($res == null) throw new DBAccessException();
        $view_model->res = $res;

        $hours = $this->restaurant_rep->getRestaurantOpeningHours($res_id);
        if($hours == null) throw new DBAccessException();
        $view_model->hours = $hours;

        $images = $this->restaurant_rep->getRestaurantImages($res_id);
        if($images == null) throw new DBAccessException();
        $view_model->images = $images;

        // Setup topper
        $view_model->topper = new YummyTopper();
        $view_model->topper->title = "Yummy CMS - Restaurant - " . $res->name;
        $view_model->topper->subtitle = "Manage yummy restaurant.";
        $view_model->topper->button_text = "View restaurant";
        $view_model->topper->button_link = '/yummy/restaurant?id=' . $res->restaurant_id;
        $view_model->topper->active_tab = -1;

        return $view_model;
    }

    public function addRestaurantImage(int $restaurant_id, string $image_path) : bool {
        $count = $this->restaurant_rep->countRestaurantImages($restaurant_id);

        if($count == null) throw new DBAccessException();
        if($count >= 10) throw new MaxCountExceededException();

        $res = $this->restaurant_rep->createRestaurantImage($restaurant_id, $image_path);

        if($res == null) throw new DBAccessException();

        return $res;
    }

    public function removeRestaurantImage(int $image_id) : bool {
        return $this->restaurant_rep->deleteRestaurantImage($image_id);
    }

    public function editRestaurant($post, $files){
        if($post['restaurant_id'] == null) throw new EmptyFieldException();
        $restaurant_id = $post['restaurant_id'];

        $res = $this->restaurant_rep->getRestaurantById($restaurant_id);
        if($res == null) throw new DBDataException();

        $args = array();

        if(isset($post['name']) && $post['name'] != $res->name) $args['name'] = $post['name'];

        if(isset($post['active']) && $post['active'] != $res->active) $args['active'] = $post['active'];
        
        if(isset($post['rating']) && $post['rating'] != $res->rating) $args['rating'] = $post['rating'];

        if(isset($post['cost_rating']) && $post['cost_rating'] != $res->cost_rating) $args['cost_rating'] = $post['cost_rating'];
        
        if(isset($post['mini_text']) && $post['mini_text'] != $res->mini_text) $args['mini_text'] = $post['mini_text'];

        if(isset($post['text']) && $post['text'] != $res->text) $args['text'] = $post['text'];

        if(isset($post['address_text']) && $post['address_text'] != $res->address_text) $args['address_text'] = $post['address_text'];

        if(isset($post['address_uri']) && $post['address_uri'] != $res->address_uri) $args['address_uri'] = $post['address_uri'];

        if(isset($post['website_link']) && $post['website_link'] != $res->website_link) $args['website_link'] = $post['website_link'];

        if(!$this->restaurant_rep->editRestaurant($restaurant_id, $args)) throw new DBAccessException();

        //if(isset($post['address_uri']) && $post['address_uri'] != $res->address_uri) $args['address_uri'] = $post['address_uri'];
    }
}