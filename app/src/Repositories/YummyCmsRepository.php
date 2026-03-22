<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class YummyCmsRepository extends Repository
{
    /**
     * returns array with two elements [0]: home_title, [1]: home_subtitle.
     * @return ?array returns array, null if an error occurred.
     */
    public function getHomeData() : ?array
    {
        $stmt = $this->connection->prepare("SELECT `home_title`, `home_subtitle` FROM `YummyCMS` WHERE `cms_id` = 1");

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC); 

        return $res == false ? null : $res;
    }
} 