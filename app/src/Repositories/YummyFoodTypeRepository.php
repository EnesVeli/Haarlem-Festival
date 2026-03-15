<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\FoodType;
use PDO;

class YummyFoodTypeRepository extends Repository
{
    public function getRestaurantTypes(string $restaurant_id): ?array
    {
        $stmt = $this->connection->prepare("SELECT `YummyFoodTypes`.`type_id`, `YummyFoodTypes`.`name`, `YummyFoodTypes`.`category` FROM `YummyFoodTypes` INNER JOIN 
        (SELECT * FROM `YummyRestaurantFoodTypes` WHERE `restaurant_id` = :restaurant_id) AS `R` ON `YummyFoodTypes`.`type_id` = `R`.`type_id`");

        $stmt->execute(['restaurant_id' => $restaurant_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, FoodType::class);
        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }

    public function getAllTypes(): ?array
    {
        $stmt = $this->connection->prepare("SELECT `type_id`, `name`, `category` FROM `YummyFoodTypes`");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, FoodType::class);

        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }
}