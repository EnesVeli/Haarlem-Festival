<?php

namespace App\Repositories;

use App\Framework\Repository;
use DateTime;
use PDO;

class EventRepository extends Repository {
    private static ?EventRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : EventRepository {
        if(self::$_instance === null) self::$_instance = new EventRepository();

        return self::$_instance;
    }

    public function createVenue(string $name, string $address) : ?int {
        $stmt = $this->connection->prepare("INSERT INTO `Venue`(`name`, `address`) VALUES (:name, :address);");

        $res = $stmt->execute(['name' => $name, 'address' => $address]);

        if($res == false) return null;

        return $this->connection->lastInsertId();
    }

    public function createEvent(int $venue_id, int $type, string $name, string $slug, string $description, DateTime $start_time, DateTime $end_time, string $image_path) : ?int {
        $stmt = $this->connection->prepare("INSERT INTO `Event`(`venue_id`, `type`, `name`, `slug`, `description`, `start_time`, `end_time`, `max_tickets`, `image_path`) 
                                            VALUES (:venue_id, :type, :name, :slug, :description, :start_time, :end_time, 20, :image_path);");

        $res = $stmt->execute(['venue_id' => $venue_id, 'type' => $type, 'name' => $name, 'slug' => $slug, 'description' => $description, 'start_time' => $start_time->format('Y-m-d H:i:s'), 
        'end_time' => $end_time->format('Y-m-d H:i:s'), 'image_path' => $image_path]);

        if($res == false) return null;

        return $this->connection->lastInsertId();
    }

    public function createTicektType(int $event_id, string $name, int $price, int $is_pay_as_you_like, DateTime $start_time, DateTime $end_time) : ?int {
        $stmt = $this->connection->prepare("INSERT INTO `Ticket_Type`(`event_id`, `name`, `price`, `is_pay_as_you_like`, `start_time`, `end_time`) 
        VALUES (:event_id, :name, :price, :is_pay_as_you_like, :start_time, :end_time);");

        $res = $stmt->execute(['event_id' => $event_id, 'name' => $name, 'price' => $price, 'is_pay_as_you_like' => $is_pay_as_you_like,
            'start_time' => $start_time->format('Y-m-d H:i:s'), 'end_time' => $end_time->format('Y-m-d H:i:s')]);

        if($res == false) return null;

        return $this->connection->lastInsertId();
    }
}