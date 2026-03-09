<?php
namespace App\Repositories;

use App\Framework\Repository;

class HomeRepository extends Repository
{
    public function getHomeContent(): array
    {
        $stmt = $this->connection->query("SELECT `key`, `value` FROM `home_content`");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $content = [];
        foreach ($rows as $row) {
            $content[$row['key']] = $row['value'];
        }
        return $content;
    }

    public function getHomeEvents(): array
    {
        $stmt = $this->connection->query(
            "SELECT * FROM `home_events` WHERE `is_active` = 1 ORDER BY `sort_order` ASC"
        );
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}