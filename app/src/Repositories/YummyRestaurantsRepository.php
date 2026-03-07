<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class YummyRestaurantsRepository extends Repository
{
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

    public function getFilteredRestaurants($all_types, $sorting) : ?array {
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

        if($filter == null){
            $query = "SELECT * FROM `YummyRestaurants`";
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
                        ON `RT`.`restaurant_id` = `R`.`restaurant_id`";
        }

        $stmt = $this->connection->prepare($query);

        if($filter == null) $stmt->execute();
        else $stmt->execute($param);

        return $stmt->fetchAll(PDO::FETCH_BOTH);  
    }
}