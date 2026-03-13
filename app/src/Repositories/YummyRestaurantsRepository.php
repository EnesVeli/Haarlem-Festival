<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\Restaurant;
use PDO;

enum RestaurantSortingOption : int{
    case NameASC = 0;
    case NameDESC = 1;
    case RatingASC = 2;
    case RatingDESC = 3;
    case CostASC = 4;
    case CostDESC = 5;
}

class YummyRestaurantsRepository extends Repository
{
    public const NUMBER_OF_RESTAURANTS_PER_PAGE = 20;

    /**
     * @param int $restaurant_id id of searched restaurant.
     * @return ?Restaurant returns restaurant object if found, null if not.
     */
    public function getRestaurantById(string $restaurant_id): ?Restaurant
    {
        $stmt = $this->connection->prepare("SELECT `restaurant_id`, `mini_img_path`, `name`, `mini_text`, `rating`, `cost_rating`, `active` FROM `YummyRestaurants` WHERE `restaurant_id` = :restaurant_id");
        $stmt->execute(['restaurant_id' => $restaurant_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);
        $res = $stmt->fetch(); 

        return $res == false ? null : $res;
    }

    /**
     * @return ?array returns 8 or less active restaurant sorted by popularity as list of objects, or null if something went wrong.
     */
    public function getTopActiveRestaurants() : ?array {
        $stmt = $this->connection->prepare("SELECT * FROM `YummyRestaurants` WHERE `active` = 1 LIMIT 8");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);
        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }   

    /**
     * @param array $all_types Array with all selected filters. If empty, no filter is applied.
     * @param int $page Selected resturant list page (e.g. if page is 0 will return resturants 0-24, if page is 1 will return 25-48 retaurants, e.t.c)
     * @param RestaurantSortingOption $sorting Sorting method applied. If empty, sorts by name ascending
     *
     * @return array array with two elements. 
     * [0] is total number of restaurants that fit filter.
     * [1] is limeted, sorted and optinaly filtered list of restaurants. 
     * Both of the elements can be null.
     */
    public function getFilteredRestaurants($all_types, $page, RestaurantSortingOption $sorting = RestaurantSortingOption::NameASC) : array {    
        if($all_types == null || count($all_types) == 0){
            $filter = null;
        }      
        else{
            $filter = 'WHERE `name` = :t0';
            $param = ['t0' => $all_types[0]];

            $count = count($all_types);

            for ($i = 1; $i < $count; $i++) { 
                $filter = $filter . ' OR `name` = :t' . $i;
                $param["t$i"] = $all_types[$i];
            }

            //echo '<pre>'; print_r($param); echo '</pre>';
        }      

        $sort_string = $this->getSortString($sorting);

        $limit = YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE;
        $offset = YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE * $page;

        if($filter == null){
            $query = "SELECT `R`.`restaurant_id`, `R`.`mini_img_path`, `R`.`name`, `R`.`mini_text`, `R`.`rating`, `R`.`cost_rating`, `R`.`active` FROM `YummyRestaurants` AS `R` WHERE `R`.`active` = 1 $sort_string LIMIT $limit OFFSET $offset";

            $count_query = "SELECT COUNT(*) FROM `YummyRestaurants` AS `R` WHERE `R`.`active` = 1";
        }
        else{
            $query =   "SELECT `R`.`restaurant_id`, `R`.`mini_img_path`, `R`.`name`, `R`.`mini_text`, `R`.`rating`, `R`.`cost_rating`, `R`.`active`
                        FROM `YummyRestaurants` AS `R`                 
                        INNER JOIN
                            (SELECT `RFT`.`restaurant_id`, `RFT`.`type_id`, COUNT(*)
                            FROM `YummyRestaurantFoodTypes` AS `RFT` 
                            INNER JOIN
                                (SELECT `type_id`, `name` 
                                FROM `YummyFoodTypes` 
                                $filter) AS `T` 
                            ON `RFT`.`type_id` = `T`.`type_id`
                            GROUP BY `RFT`.`restaurant_id`
                            HAVING COUNT(*) >= $count) AS `RT`
                        ON `RT`.`restaurant_id` = `R`.`restaurant_id`
                        WHERE `R`.`active` = 1
                        $sort_string
                        LIMIT $limit OFFSET $offset";

            $count_query = "SELECT COUNT(*)
                        FROM `YummyRestaurants` AS `R`                 
                        INNER JOIN
                            (SELECT `RFT`.`restaurant_id`, `RFT`.`type_id`, COUNT(*)
                            FROM `YummyRestaurantFoodTypes` AS `RFT` 
                            INNER JOIN
                                (SELECT `type_id`, `name` 
                                FROM `YummyFoodTypes` 
                                $filter) AS `T` 
                            ON `RFT`.`type_id` = `T`.`type_id`
                            GROUP BY `RFT`.`restaurant_id`
                            HAVING COUNT(*) >= $count) AS `RT`
                        ON `RT`.`restaurant_id` = `R`.`restaurant_id`
                        WHERE `R`.`active` = 1";
        }

        $output = [];
        
        // Get total number of restaurants that fit filter
        $stmt_count = $this->connection->prepare($count_query);

        if($filter == null) $stmt_count->execute();
        else $stmt_count->execute($param);

        $res_number = $stmt_count->fetch(PDO::FETCH_BOTH);

        array_push($output, $res_number == false ? 0 : $res_number[0]);


        // Get list of restaurants limited, filtered and sorted
        $stmt = $this->connection->prepare($query);

        if($filter == null) $stmt->execute();
        else $stmt->execute($param);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);

        array_push($output, $stmt->fetchAll());
       
        
        // Return limited list and total count
        return $output;
    }

    /**
     * @param RestaurantSortingOption $sorting desired type of sorting
     * @return string returns sql associated with selected sorting type. If unknown sorting type is passed, returns empty string.
     */
    private function getSortString(RestaurantSortingOption $sorting) : string {
        switch($sorting){
            case RestaurantSortingOption::NameASC:
                return "ORDER BY `R`.`name` ASC";
            case RestaurantSortingOption::NameDESC:
                return "ORDER BY `R`.`name` DESC";
            case RestaurantSortingOption::RatingASC:
                return "ORDER BY `R`.`rating` ASC";
            case RestaurantSortingOption::RatingDESC:
                return "ORDER BY `R`.`rating` DESC";
            case RestaurantSortingOption::CostASC:
                return "ORDER BY `R`.`cost_rating` ASC, `R`.`name` ASC";
            case RestaurantSortingOption::CostDESC:
                return "ORDER BY `R`.`cost_rating` DESC, `R`.`name` DESC";
            default:
                return "";
        }
    }
}