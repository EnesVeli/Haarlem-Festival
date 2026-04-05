<?php

namespace App\Services\Yummy;

use App\Models\Exceptions\DBAccessException;
use App\Models\Exceptions\DBDataException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\MaxCountExceededException;
use App\Models\Restaurant;
use App\Repositories\YummyCmsRepository;
use App\Repositories\YummyRestaurantsRepository;
use App\ViewModels\Yummy\Cms\YummyHomeViewModel;
use App\ViewModels\Yummy\Cms\YummyListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantListViewModel;
use App\ViewModels\Yummy\Cms\YummyRestaurantViewModel;
use App\ViewModels\Yummy\Cms\YummyTopper;
use PDO;
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

    public function editHome(string $title, string $subtitle, $image){
        if($image != null){
            $img_path = $this->addImageToDir('yummy/topper/', $image['name'], $image['tmp_name']);   
            
            $old_image_path = $this->cms_rep->getHomeImage();
        }     

        $this->cms_rep->updateHomeData($title, $subtitle, $img_path);

        if($img_path != null && $old_image_path != null){
            $this->deleteImageFromDir('yummy/topper/', $old_image_path); 
        }
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

    public function editList(string $title, string $subtitle, ?array $image){
        if($image != null){
            $img_path = $this->addImageToDir('yummy/topper/', $image['name'], $image['tmp_name']);   
            
            $old_image_path = $this->cms_rep->getListImage();
        }     

        $this->cms_rep->updateListData($title, $subtitle, $img_path);

        if($img_path != null && $old_image_path != null){
            $this->deleteImageFromDir('yummy/topper/', $old_image_path); 
        }
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

        // Calculate offset and limit for page number selection
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

    public function addRestaurantImage(int $restaurant_id, $image) : bool {
        if(!isset($image)) throw new EmptyFieldException();

        $count = $this->restaurant_rep->countRestaurantImages($restaurant_id);

        if($count == null) throw new DBAccessException();
        if($count >= 10) throw new MaxCountExceededException();

        $image_path = $this->addImageToDir('yummy/restaurants/', $image['name'], $image['tmp_name']);
        if($image_path == null) throw new DBAccessException();

        $res = $this->restaurant_rep->createRestaurantImage($restaurant_id, $image_path);

        if($res == null) throw new DBAccessException();

        return $res;
    }

    public function removeRestaurantImage(int $image_id) : bool {
        return $this->restaurant_rep->deleteRestaurantImage($image_id);
    }

    /**
     * Edit restaurant.
     * @param mixed $post $_POST
     * @param mixed $files $_FILES
     * @throws EmptyFieldException if restaurant_id in $post is null or empty.
     * @throws DBAccessException if there are any error during query execution.
     * @return bool true if at least one field was edited, if no changes were made returns false.
     */
    public function editRestaurant($post, $files) : bool {
        if($post['restaurant_id'] == null) throw new EmptyFieldException();
        $restaurant_id = $post['restaurant_id'];

        // Edit restaurant 
        $res = $this->restaurant_rep->getRestaurantById($restaurant_id);
        if($res == null) throw new DBAccessException();

        $values = array();
        $fields = array();
        $types = array();

        if(isset($files['main_img_path']['tmp_name'])){
            $main_image = $this->addImageToDir('yummy/restaurants/', $files['main_img_path']['name'], $files['main_img_path']['tmp_name']);

            if($main_image != null){
                $this->addFieldForce($values, $fields, $types, 'main_img_path', $main_image, PDO::PARAM_STR);
            }
        }

        $this->addField($values, $fields, $types, 'name',         $res->name,         $post['name'],          PDO::PARAM_STR);
        $this->addField($values, $fields, $types, 'active',  (int)$res->active,  (int)$post['active'],        PDO::PARAM_INT);
        $this->addField($values, $fields, $types, 'rating',       $res->rating,       $post['rating'],        PDO::PARAM_STR);
        $this->addField($values, $fields, $types, 'cost_rating',  $res->cost_rating,  $post['cost_rating'],   PDO::PARAM_INT);
        $this->addField($values, $fields, $types, 'mini_text',    $res->mini_text,    $post['mini_text'],     PDO::PARAM_STR);
        $this->addField($values, $fields, $types, 'text',         $res->text,         $post['text'],          PDO::PARAM_STR);
        $this->addField($values, $fields, $types, 'address_text', $res->address_text, $post['address_text'],  PDO::PARAM_STR);
        $this->addField($values, $fields, $types, 'address_uri',  $res->address_uri,  $post['address_uri'],   PDO::PARAM_STR);
        $this->addField($values, $fields, $types, 'website_link', $res->website_link, $post['website_link'],  PDO::PARAM_STR);

        $edit_restaurant = $this->restaurant_rep->editRestaurant($restaurant_id, $values, $fields, $types);
        if($edit_restaurant != null && !$edit_restaurant) throw new DBAccessException();

        if(isset($main_image)) $this->deleteImageFromDir('yummy/restaurants/', $res->main_img_path); // Delete old main image

        // Edit opening hours 
        $hours = $this->restaurant_rep->getRestaurantOpeningHours($restaurant_id);
        if($hours == null) throw new DBAccessException();
        
        $values = array();
        $fields = array();

        $this->addFieldTypeless($values, $fields, 'monday',    $hours->monday,    $post['opening_hours_monday']);
        $this->addFieldTypeless($values, $fields, 'tuesday',   $hours->tuesday,   $post['opening_hours_tuesday']);
        $this->addFieldTypeless($values, $fields, 'wednesday', $hours->wednesday, $post['opening_hours_wednesday']);
        $this->addFieldTypeless($values, $fields, 'thursday',  $hours->thursday,  $post['opening_hours_thursday']);
        $this->addFieldTypeless($values, $fields, 'friday',    $hours->friday,    $post['opening_hours_friday']);
        $this->addFieldTypeless($values, $fields, 'saturday',  $hours->saturday,  $post['opening_hours_saturday']);
        $this->addFieldTypeless($values, $fields, 'sunday',    $hours->sunday,    $post['opening_hours_sunday']);

        $edit_hours = $this->restaurant_rep->editOpeningHours($restaurant_id, $values, $fields);
        if($edit_hours != null && !$edit_hours) throw new DBAccessException();

        // Edit additional images
        $images_count = $this->restaurant_rep->countRestaurantImages($restaurant_id);
        if($images_count == null) throw new DBAccessException();

        $add_image_edit = null;
        
        for ($i = 0; $i < $images_count; $i++) { 
            if(isset($files['additional_image_' . $i]) && isset($post['additional_image_id_' . $i])){
                $new_path = $this->addImageToDir('yummy/restaurants/', $files['additional_image_' . $i]['name'], $files['additional_image_' . $i]['tmp_name']);

                if($new_path == null) continue;

                $id = $post['additional_image_id_' . $i];

                $old_path = $this->restaurant_rep->getRestaurantImagePath($id);

                $edit = $this->restaurant_rep->editRestaurantImage($id, $new_path);

                if($edit == false) throw new DBAccessException();

                if($edit) $add_image_edit = true;

                if(isset($old_path)) $this->deleteImageFromDir('yummy/restaurants/', $old_path);
            }
        }

        if($edit_restaurant == null && $edit_hours == null && $add_image_edit == null) return false;

        return true;
    }

    private function addFieldTypeless(array& $values, array& $fields, string $field_query, $old_value, $new_value){
        if(isset($new_value) && $new_value != $old_value){
            array_push($values, $new_value);
            array_push($fields, $field_query);
        }
    }

    private function addField(array& $values, array& $fields, array& $types, string $field_query, $old_value, $new_value, $type){
        if(isset($new_value) && $new_value != $old_value){
            array_push($values, $new_value);
            array_push($fields, $field_query);
            array_push($types, $type);
        }
    }

    private function addFieldForce(array& $values, array& $fields, array& $types, string $field_query, $new_value, $type){
        array_push($values, $new_value);
        array_push($fields, $field_query);
        array_push($types, $type);
    }

    /**
     * Moves file from uploads to specified directory in uploads folder.
     * @param string $end_dir relative to uploads folder path do directory (e.g. 'yummy/topper/'), path must end with '/'.
     * @param mixed $origin_name name of origin file.
     * @param mixed $tmp_name tmp name of uploded file.
     * @return ?string on success returns new file name with extention. On fail returns null.
     */
    private function addImageToDir(string $end_dir, $origin_name, $tmp_name) : ?string {
        if($tmp_name == null) return null;

        // Crafting path
        $file_name = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($origin_name, PATHINFO_EXTENSION);
        $path = __DIR__ . '/../../../public/assets/uploads/' . $end_dir . $file_name;

        if(move_uploaded_file($tmp_name, $path)) return $file_name;
        
        return null;
    }

    /**
     * Deletes file from specified directory in uploads folder.
     * @param string $end_dir relative to uploads folder path do directory (e.g. 'yummy/topper/'), path must end with '/'.
     * @param mixed $file_name name of origin file.
     * @return bool true on success, false on failure.
     */
    private function deleteImageFromDir(string $end_dir, $file_name) : bool {
        if($file_name == null) return false;

        // Crafting path
        $path = __DIR__ . '/../../../public/assets/uploads/' . $end_dir . $file_name;

        return unlink($path);
    }
}