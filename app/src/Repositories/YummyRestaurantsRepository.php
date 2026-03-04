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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }
}