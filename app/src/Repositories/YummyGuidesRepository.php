<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class YummyGuidesRepository extends Repository
{
    public function getGuideById(string $guide_id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `YummyGuides` WHERE `guide_id` = :guide_id");
        $stmt->execute(['guide_id' => $guide_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);  
    }

    public function getAllActiveGuides() : ?array {
        $stmt = $this->connection->prepare("SELECT * FROM `YummyGuides` WHERE `active` = 1");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_BOTH);  
    }
}