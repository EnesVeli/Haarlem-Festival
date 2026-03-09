<?php
namespace App\Repositories;

use App\Framework\Repository;
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

    public function getRestaurantById(string $restaurant_id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `YummyRestaurants` WHERE `restaurant_id` = :restaurant_id");
        $stmt->execute(['restaurant_id' => $restaurant_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);  
    }

    public function getTopActiveRestaurants() : ?array {
        $stmt = $this->connection->prepare("SELECT * FROM `YummyRestaurants` WHERE `active` = 1 LIMIT 8");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_BOTH);  
    }   

    /**
     * @param array $all_types Array with all selected filters. If empty, no filter is applied.
     * @param int $page Selected resturant list page (e.g. if page is 0 will return resturants 0-24, if page is 1 will return 25-48 retaurants, e.t.c)
     * @param RestaurantSortingOption $sorting Sorting method applied. If empty, sorts by name ascending
     *
     * @return array array with two elements. 
     * [0] is limeted, sorted and optinaly filtered list of restaurants. 
     * [1] is total number of restaurants that fit filter.
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
            $query = "SELECT * FROM `YummyRestaurants` AS `R` WHERE `R`.`active` = 1 $sort_string LIMIT $limit OFFSET $offset";

            $count_query = "SELECT COUNT(*) FROM `YummyRestaurants` AS `R` WHERE `R`.`active` = 1";
        }
        else{
            $query =   "SELECT *
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

        // Get list of restaurants limited, filtered and sorted
        $stmt = $this->connection->prepare($query);

        if($filter == null) $stmt->execute();
        else $stmt->execute($param);

        array_push($output, $stmt->fetchAll(PDO::FETCH_BOTH));
        
        // Get total number of restaurants that fit filter
        $stmt_count = $this->connection->prepare($count_query);

        if($filter == null) $stmt_count->execute();
        else $stmt_count->execute($param);

        $res_number = $stmt_count->fetch(PDO::FETCH_BOTH);

        array_push($output, $res_number == null ? 0 : $res_number[0]);
        
        // Return limited list and total count
        return $output;
    }

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