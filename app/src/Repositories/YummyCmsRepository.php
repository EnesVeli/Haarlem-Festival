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
     * updates home_title, home_subtitle and optinaly home_image in db.
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function updateHomeData(string $home_title, string $home_subtitle, ?string $home_image) : bool
    {
        if($home_image == null){
            $sql = "UPDATE `YummyCMS` SET `home_title`=:home_title, `home_subtitle`=:home_subtitle WHERE `cms_id`= 1;";
            $arg = ['home_title' => $home_title, 'home_subtitle' => $home_subtitle];
        }
        else{
            $sql = "UPDATE `YummyCMS` SET `home_title`=:home_title, `home_subtitle`=:home_subtitle, `home_image`=:home_image WHERE `cms_id`= 1;";
            $arg = ['home_title' => $home_title, 'home_subtitle' => $home_subtitle, 'home_image' => $home_image];
        }

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute($arg);
    }

    /**
     * returns array with three elements [0]: list_title, [1]: list_subtitle, [2]: list_image.
     * @return ?array returns array, null if an error occurred.
     */
    public function getListData() : ?array
    {
        $stmt = $this->connection->prepare("SELECT `list_title`, `list_subtitle`, `list_image` FROM `YummyCMS` WHERE `cms_id` = 1");

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC); 

        return $res == false ? null : $res;
    }

    /**
     * updates home_title, home_subtitle and optionaly home_image in db.
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function updateListData(string $list_title, string $list_subtitle, ?string $list_image) : bool
    {
        if($list_image == null){
            $sql = "UPDATE `YummyCMS` SET `list_title`=:list_title, `list_subtitle`=:list_subtitle WHERE `cms_id`= 1;";
            $arg = ['list_title' => $list_title, 'list_subtitle' => $list_subtitle];
        }
        else{
            $sql = "UPDATE `YummyCMS` SET `list_title`=:list_title, `list_subtitle`=:list_subtitle, `list_image`=:list_image WHERE `cms_id`= 1;";
            $arg = ['list_title' => $list_title, 'list_subtitle' => $list_subtitle, 'list_image' => $list_image];
        }

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute($arg);
    }
} 