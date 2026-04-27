<?php
namespace App\Repositories;

use App\Framework\Repository;

class HomeRepository extends Repository
{
    private static ?HomeRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : HomeRepository {
        if(self::$_instance === null) self::$_instance = new HomeRepository();

        return self::$_instance;
    }

    // ─── READ ────────────────────────────────────────────────────────────────

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

    public function getAllHomeEvents(): array
    {
        $stmt = $this->connection->query(
            "SELECT * FROM `home_events` ORDER BY `sort_order` ASC"
        );
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ─── WRITE ───────────────────────────────────────────────────────────────

    /**
     * Upsert an array of key/value pairs into home_content.
     */
    public function saveHomeContent(array $data): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `home_content` (`key`, `value`)
             VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)"
        );

        foreach ($data as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
    }

    /**
     * Insert or update a home_events row.
     */
    public function saveHomeEvent(?int $id, array $data): void
    {
        if ($id === null) {
            // INSERT
            $stmt = $this->connection->prepare(
                "INSERT INTO `home_events`
                 (`title`, `category`, `short_description`, `long_description`,
                  `venues`, `url`, `button_label`, `icon`, `bg_class`, `image`,
                  `sort_order`, `is_active`)
                 VALUES
                 (:title, :category, :short_description, :long_description,
                  :venues, :url, :button_label, :icon, :bg_class, :image,
                  :sort_order, :is_active)"
            );
        } else {
            // UPDATE
            $stmt = $this->connection->prepare(
                "UPDATE `home_events` SET
                  `title`             = :title,
                  `category`          = :category,
                  `short_description` = :short_description,
                  `long_description`  = :long_description,
                  `venues`            = :venues,
                  `url`               = :url,
                  `button_label`      = :button_label,
                  `icon`              = :icon,
                  `bg_class`          = :bg_class,
                  `image`             = :image,
                  `sort_order`        = :sort_order,
                  `is_active`         = :is_active
                 WHERE `id` = :id"
            );
            $data[':id'] = $id;
        }

        $params = [
            ':title'             => $data['title'],
            ':category'          => $data['category'],
            ':short_description' => $data['short_description'],
            ':long_description'  => $data['long_description'],
            ':venues'            => $data['venues'],
            ':url'               => $data['url'],
            ':button_label'      => $data['button_label'],
            ':icon'              => $data['icon'],
            ':bg_class'          => $data['bg_class'],
            ':image'             => $data['image'] ?? null,
            ':sort_order'        => $data['sort_order'],
            ':is_active'         => $data['is_active'],
        ];

        if ($id !== null) {
            $params[':id'] = $id;
        }

        $stmt->execute($params);
    }

    public function deleteHomeEvent(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM `home_events` WHERE `id` = :id");
        $stmt->execute([':id' => $id]);
    }
}