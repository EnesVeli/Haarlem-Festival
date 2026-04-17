<?php
namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Framework\Repository;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\YummyBooking;
use PDO;

class OrderRepository extends Repository
{
    /**
     * Gets order by user_id and status form db.
     * @param int $user_id 
     * @param OrderStatus $status
     * @return ?Order returns if order was found returns it, otherwise, returns null.
     */
    public function getOrderByUserIdAndStatus(int $user_id, OrderStatus $status) : ?Order {
        $stmt = $this->connection->prepare("SELECT `order_id`, `user_id`, `date` AS `date_`, `status` AS `status_`, `total_price` FROM `Orders` WHERE `status` = :status AND `user_id` = :user_id;");

        $stmt->bindValue('status', (int)$status->value, PDO::PARAM_INT);
        $stmt->bindValue('user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }  

    /**
     * Creates an cart order in db.
     * Method does not check if there is already an cart order. If it is the case, it might break cart for the user.
     * @param int $user_id
     * @return ?int returns index of a newly created order, if operation was successfull. Otherwise returns null.
     */
    public function createCartOrder(int $user_id) : ?int{
        $sql = "INSERT INTO `Orders`(`user_id`, `date`, `status`, `total_price`) VALUES (:user_id, NULL, 0, NULL);";

        $stmt = $this->connection->prepare($sql);

        $res = $stmt->execute(['user_id' => $user_id]);
        if($res == false) return null;

        return $this->connection->lastInsertId();
    }

    /**
     * Creates an order item in db.
     * @param OrderItem $item uses all of the $item fields to create an OrderItem (except for item_id).
     * @return ?int returns index of a newly created order item, if operation was successfull. Otherwise returns null.
     */
    public function createOrderItem(OrderItem $item) : ?int {
        $sql = "INSERT INTO `OrderItems`(`order_id`, `booking_id`, `booking_type`, `price`) VALUES (:order_id, :booking_id, :booking_type, :price);";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('order_id', $item->order_id, PDO::PARAM_INT);
        $stmt->bindValue('booking_id', $item->booking_id, PDO::PARAM_INT);
        $stmt->bindValue('booking_type', (int)$item->booking_type->value, PDO::PARAM_INT);
        $stmt->bindValue('price', $item->price, PDO::PARAM_INT);

        $res = $stmt->execute();
        if($res == false) return null;

        return $this->connection->lastInsertId();
    }

    /**
     * Gets array of order's OrderItems.
     * @param int $order_id
     * @return ?array returns array of OrderItem on success, null on failure.
     */
    public function getOrderOrderItems(int $order_id) : ?array
    {
        $stmt = $this->connection->prepare("SELECT `item_id`, `order_id`, `booking_id`, `booking_type` AS `booking_type_`, `price` FROM `OrderItems` WHERE `order_id` = :order_id;");
        $stmt->execute(['order_id' => $order_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, OrderItem::class);
        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }

    public function getYummyBookingById(int $booking_id) : ?YummyBooking {
        $stmt = $this->connection->prepare("SELECT `booking_id`, `reservation_id`, `date` AS `date_`, `adult_number`, `child_number`, `comment`
            FROM `YummyBookings` WHERE `booking_id` = :booking_id;");

        $stmt->execute(['booking_id' => $booking_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, YummyBooking::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

     /**
     * Creates a restaurant booking in the db.
     * @param YummyBooking $booking booking you want to create
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function createBooking(YummyBooking $booking) : ?int {
        $sql = "INSERT INTO `YummyBookings`(`reservation_id`, `user_id`, `date`, `adult_number`, `child_number`, `comment`) 
                VALUES (:reservation_id, :user_id, :date, :adult_number , :child_number, :comment);";

        $stmt = $this->connection->prepare($sql);

        $res = $stmt->execute(['reservation_id' => $booking->reservation_id,
                               'user_id' => $booking->user_id,
                               'date' => $booking->date,
                               'adult_number' => $booking->adult_number,
                               'child_number' => $booking->child_number,
                               'comment' => $booking->comment]);

        if($res == false) return null; 

        return $this->connection->lastInsertId();
    }

    /**
     * Creates a restaurant booking in the db.
     * @param YummyBooking $booking booking you want to create
     * @param int $date_offset offset in days from today (i. e. today + offset(number of days) will be put in date, instead of $booking date value).
     * @return bool returns new booking id, if operation was successfull. Otherwise null.
     */
    public function createBookingWithOffest(YummyBooking $booking, int $date_offset) : ?int {
        $sql = "INSERT INTO `YummyBookings`(`reservation_id`, `date`, `adult_number`, `child_number`, `comment`) 
                VALUES (:reservation_id, DATE(NOW()) + INTERVAL +:date_offset DAY, :adult_number , :child_number, :comment);";

        $stmt = $this->connection->prepare($sql);

        $res = $stmt->execute(['reservation_id' => $booking->reservation_id,
                               'date_offset' => $date_offset,
                               'adult_number' => $booking->adult_number,
                               'child_number' => $booking->child_number,
                               'comment' => $booking->comment]);

        if($res == false) return null; 

        return $this->connection->lastInsertId();
    }
}