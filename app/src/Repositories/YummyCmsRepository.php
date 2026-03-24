<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class YummyCmsRepository extends Repository
{
    /**
     * returns array with three elements [0]: home_title, [1]: home_subtitle, [2]: home_image.
     * @return ?array returns array, null if an error occurred.
     */
    public function getHomeData() : ?array
    {
        $stmt = $this->connection->prepare("SELECT `home_title`, `home_subtitle`, `home_image` FROM `YummyCMS` WHERE `cms_id` = 1");

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC); 

        return $res == false ? null : $res;
    }

    /**
     * updates home_title, home_subtitle and home_image in db.
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function updateHomeData(string $home_title, string $home_subtitle, string $home_image) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `YummyCMS` SET `home_title` = ':home_title', `home_subtitle` = ':home_subtitle', `home_image` = ':home_image' WHERE `cms_id`= 1");

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC); 

        return $res == false ? null : $res;
    }
} 