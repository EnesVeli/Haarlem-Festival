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

    public function createRestaurantType(int $restaurant_id, int $type_id) : bool {
        $stmt = $this->connection->prepare("INSERT INTO `YummyRestaurantFoodTypes`(`restaurant_id`, `type_id`) VALUES (:restaurant_id ,:type_id);");

        return $stmt->execute(['restaurant_id' => $restaurant_id, 'type_id' => $type_id]);
    }

    public function getTypeByName(int $name) : ?FoodType {
        $stmt = $this->connection->prepare("SELECT `type_id`, `name`, `category` FROM `YummyFoodTypes` WHERE `name` = :name;");

        $stmt->execute(['name' => $name]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, FoodType::class);

        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }

    public function getRestaurantTypeById(int $restaurant_id, int $type_id) : ?array {
        $stmt = $this->connection->prepare("SELECT `id`, `restaurant_id`, `type_id` FROM `YummyRestaurantFoodTypes` WHERE `restaurant_id` = :restaurant_id AND `type_id` = :type_id LIMIT 1;");

        $stmt->execute(['restaurant_id' => $restaurant_id, 'type_id' => $type_id]);

        $res = $stmt->fetch(PDO::FETCH_BOTH);

        return $res == false ? null : $res;
    }

    public function deleteRestaurantTypeById(int $restaurant_id, int $type_id){ 
        $stmt = $this->connection->prepare("DELETE FROM `YummyRestaurantFoodTypes` WHERE `restaurant_id` = :restaurant_id AND `type_id` = :type_id");

        return $stmt->execute(['restaurant_id' => $restaurant_id, 'type_id' => $type_id]);
    }
}