<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\Guide;
use PDO;

class YummyGuidesRepository extends Repository
{
    private static ?YummyGuidesRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : YummyGuidesRepository {
        if(self::$_instance === null) self::$_instance = new YummyGuidesRepository();

        return self::$_instance;
    }

    /**
     * @param int $guide_id id of searched guid.
     * @return ?Guide returns guide object if found, null if not.
     */
    public function getGuideById(string $guide_id): ?Guide
    {
        $stmt = $this->connection->prepare("SELECT `guide_id`, `mini_img_path`, `mini_title`, `mini_text`, `active` FROM `YummyGuides` WHERE `guide_id` = :guide_id");
        $stmt->execute(['guide_id' => $guide_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Guide::class);
        $res = $stmt->fetch();  

        return $res == false ? null : $res;
    }

    /**
     * @return ?array returns list of 8 or less guide objects, or null if something went wrong.
     */
    public function getTopActiveGuides() : ?array {
        $stmt = $this->connection->prepare("SELECT `guide_id`, `mini_img_path`, `mini_title`, `mini_text`, `active` FROM `YummyGuides` WHERE `active` = 1 LIMIT 8");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Guide::class);
        $res = $stmt->fetchAll();  

        return $res == false ? null : $res; 
    }
}