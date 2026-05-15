<?php
namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Framework\Repository;
use App\Models\ExportOrder;
use App\Models\History\HistoryBooking;
use App\Models\Jazz\JazzBooking;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoryBooking;
use App\Models\YummyBooking;
use App\ViewModels\OrderCmsExportParam;
use DateTime;
use PDO;

class OrderRepository extends Repository
{
    private static ?OrderRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : OrderRepository {
        if(self::$_instance === null) self::$_instance = new OrderRepository();

        return self::$_instance;
    }

    /**
     * Gets order by user_id and status form db.
     * @param int $user_id 
     * @param OrderStatus $status
     * @return ?Order returns if order was found returns it, otherwise, returns null. 
     */
    public function getOrderByUserIdAndStatus(int $user_id, OrderStatus $status) : ?Order {
        $stmt = $this->connection->prepare("SELECT `order_id`, `user_id`, `date` AS `date_`, `status` AS `status_`, `total_price`, `stripe_session` FROM `Orders` WHERE `status` = :status AND `user_id` = :user_id;");

        $stmt->bindValue('status', (int)$status->value, PDO::PARAM_INT);
        $stmt->bindValue('user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }  

    /**
     * Gets order by its id form db.
     * @param int $order_id 
     * @return ?Order returns if order was found returns it, otherwise, returns null. 
     */
    public function getOrderById(int $order_id) : ?Order {
        $stmt = $this->connection->prepare("SELECT `order_id`, `user_id`, `date` AS `date_`, `status` AS `status_`, `total_price`, `stripe_session` FROM `Orders` WHERE `order_id` = :order_id;");

        $stmt->bindValue('order_id', $order_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }  

    /**
     * Gets array of orders for given user with status: Paid or NotPaid.
     * @param int $user_id id of searched user.
     * @return Order[]|null|bool retuns false if there were any errors. If nothing found returns null. If something found, returns array of orders.
     */
    public function getUserNonCartOrders(int $user_id) : array {
        $stmt = $this->connection->prepare("SELECT `order_id`, `user_id`, `date` AS `date_`, `status` AS `status_`, `total_price`, `stripe_session` FROM `Orders` WHERE `user_id` = :user_id AND (`status` = 1 OR `status` = 2);");

        $stmt->bindValue('user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
        return $stmt->fetchAll();
    }

    /**
     * Updates status of the order.
     * @param int $order_id id of the order.
     * @param OrderStatus $status new status.
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function updateOrderStatus(int $order_id, OrderStatus $status) : bool { 
        $stmt = $this->connection->prepare("UPDATE `Orders` SET `status`=:status WHERE `order_id` = :order_id;;");

        $stmt->bindValue('order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindValue('status', $status->value, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Updates order to not_paid (sets its status to NotPaid and sets its total price)
     * @param Order $order the order
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function updateOrderToNotPaid(Order $order) : bool { 
        $stmt = $this->connection->prepare("UPDATE `Orders` SET `status`=:status,`total_price`=:total_price, `stripe_session`=:stripe_session, `date`=:date WHERE `order_id` = :order_id;");

        $stmt->bindValue('order_id', $order->order_id, PDO::PARAM_INT);
        $stmt->bindValue('status', OrderStatus::NotPaid->value, PDO::PARAM_INT);
        $stmt->bindValue('total_price', $order->total_price, PDO::PARAM_INT);
        $stmt->bindValue('stripe_session', $order->stripe_session, PDO::PARAM_STR);
        $stmt->bindValue('date', $order->date->format('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Updates order to paid (sets its status to Paid, sets its order date to now(), and sets its total price)
     * @param Order $order the order
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function updateOrderToPaid(Order $order) : bool { 
        $stmt = $this->connection->prepare("UPDATE `Orders` SET `date`=:date,`status`=:status WHERE `order_id` = :order_id;");

        $stmt->bindValue('order_id', $order->order_id, PDO::PARAM_INT);
        $stmt->bindValue('status', OrderStatus::Paid->value, PDO::PARAM_INT);
        $stmt->bindValue('date', $order->date->format('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
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
     * @return ?array|bool returns array of OrderItem on success, null if no items found. Returns false if there were errors during execution.
     */
    public function getOrderOrderItems(int $order_id) : ?array
    {
        $stmt = $this->connection->prepare("SELECT `item_id`, `order_id`, `booking_id`, `booking_type` AS `booking_type_`, `price` FROM `OrderItems` WHERE `order_id` = :order_id;");
        $stmt->execute(['order_id' => $order_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, OrderItem::class);
        $res = $stmt->fetchAll();

        return $res;
    }

    /**
     * Removes order items in db (should ONLY be used onto non-paid orders).
     * @param int $order_id id of the order.
     * @param OrderStatus $status status of remove order. (If paid, imidiatly fails remove)
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function removeOrder(int $order_id, OrderStatus $status) : bool {
        if($status === OrderStatus::Paid) return false;

        $stmt = $this->connection->prepare("DELETE FROM `Orders` WHERE `order_id` = :order_id;");

        $stmt->bindValue('order_id', $order_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Removes order items in db.
     * @param int $item_id id of the order item.
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function removeOrderItem(int $item_id) : bool {
        $stmt = $this->connection->prepare("DELETE FROM `OrderItems` WHERE `item_id` = :item_id;");

        $stmt->bindValue('item_id', $item_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets order item by its id.
     * @param int $item_id id of the order item.
     * @return OrderItem|bool|null returns order item if it was found. If it was not found, returns null. If there were errors during execution returns false;
     */
    public function getOrderItemById(int $item_id) : ?OrderItem{
        $stmt = $this->connection->prepare("SELECT `item_id`, `order_id`, `booking_id`, `booking_type` AS `booking_type_`, `price` FROM `OrderItems` WHERE `item_id` = :item_id;");

        $stmt->execute(['item_id' => $item_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, OrderItem::class);
        return $stmt->fetch();     
    }

    /**
     * Gets YummyBooking by id.
     * @param int $booking_id id of the booking.
     * @return ?YummyBooking returns booking if it was found, otherwise, returns null. 
     */
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
     * @return ?int returns id of new booking if operation was successfull, otherwise null.
     */
    public function createYummyBooking(YummyBooking $booking) : ?int {
        $sql = "INSERT INTO `YummyBookings`(`reservation_id`, `date`, `adult_number`, `child_number`, `comment`) 
                VALUES (:reservation_id, :date, :adult_number , :child_number, :comment);";

        $stmt = $this->connection->prepare($sql);

        $res = $stmt->execute(['reservation_id' => $booking->reservation_id,
                               'date' => $booking->date->format('Y-m-d H:i:s'),
                               'adult_number' => $booking->adult_number,
                               'child_number' => $booking->child_number,
                               'comment' => $booking->comment]);

        if($res == false) return null; 

        return $this->connection->lastInsertId();
    }

    /**
     * Removes YummyBooking by its id.
     * @param int $booking_id id of the booking.
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function removeYummyBooking(int $booking_id) : bool {
        $stmt = $this->connection->prepare("DELETE FROM `YummyBookings` WHERE `booking_id` = :booking_id;");

        $stmt->bindValue('booking_id', $booking_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets HistoryBooking by id.
     * @param int $booking_id id of the booking.
     * @return ?HistoryBooking returns booking if it was found, otherwise, returns null. 
     */
    public function getHistoryBookingById(int $booking_id) : ?HistoryBooking {
        $stmt = $this->connection->prepare("SELECT `booking_id`, `reservation_id`, `date` AS `date_`, `language`, `individual_count`, `family_count`
                FROM `HistoryBookings` WHERE `booking_id` = :booking_id;");

        $stmt->execute(['booking_id' => $booking_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, HistoryBooking::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

     /**
     * Creates a history booking in the db.
     * @param HistoryBooking $booking booking you want to create
     * @return ?int returns id of new booking if operation was successfull, otherwise null.
     */
    public function createHistoryBooking(HistoryBooking $booking) : ?int {
        $sql = "INSERT INTO `HistoryBookings`(`reservation_id`, `date`, `language`, `individual_count`, `family_count`) 
                    VALUES (:reservation_id, :date, :language, :individual_count, :family_count);";

        $stmt = $this->connection->prepare($sql);

        $res = $stmt->execute(['reservation_id' => $booking->reservation_id,
                               'date' => $booking->date->format('Y-m-d H:i:s'),
                               'language' => $booking->language,
                               'individual_count' => $booking->individual_count,
                               'family_count' => $booking->family_count]);

        if($res == false) return null; 

        return $this->connection->lastInsertId();
    }

    /**
     * Removes HistoryBooking by its id.
     * @param int $booking_id id of the booking.
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function removeHistoryBooking(int $booking_id) : bool {
        $stmt = $this->connection->prepare("DELETE FROM `HistoryBookings` WHERE `booking_id` = :booking_id;");

        $stmt->bindValue('booking_id', $booking_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets StoryBooking by id.
     * @param int $booking_id id of the booking.
     * @return ?StoryBooking returns booking if it was found, otherwise, returns null. 
     */
    public function getStoryBookingById(int $booking_id) : ?StoryBooking {
        $stmt = $this->connection->prepare("SELECT `booking_id`, `event_id`, `pay_as_you_like`, `quantity`, `haarlem_pass`, `haarlem_pass_code` 
                FROM `StoryBookings` WHERE `booking_id` = :booking_id;");

        $stmt->execute(['booking_id' => $booking_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, StoryBooking::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

     /**
     * Creates a history booking in the db.
     * @param StoryBooking $booking booking you want to create
     * @return ?int returns id of new booking if operation was successfull, otherwise null.
     */
    public function createStoryBooking(StoryBooking $booking) : ?int {
        $sql = "INSERT INTO `StoryBookings`(`event_id`, `pay_as_you_like`, `quantity`, `haarlem_pass`, `haarlem_pass_code`) 
                VALUES (:event_id, :pay_as_you_like, :quantity, :haarlem_pass, :haarlem_pass_code);";     

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('event_id', $booking->event_id, PDO::PARAM_INT);
        $stmt->bindValue('pay_as_you_like', $booking->pay_as_you_like, PDO::PARAM_INT);
        $stmt->bindValue('quantity', $booking->quantity, PDO::PARAM_INT);
        $stmt->bindValue('haarlem_pass', (int)$booking->haarlem_pass, PDO::PARAM_INT);
        $stmt->bindValue('haarlem_pass_code', $booking->haarlem_pass_code, PDO::PARAM_STR);

        $res = $stmt->execute();     

        if($res == false) return null; 

        return $this->connection->lastInsertId();
    }

    /**
     * Removes StoryBooking by its id.
     * @param int $booking_id id of the booking.
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function removeStoryBooking(int $booking_id) : bool {
        $stmt = $this->connection->prepare("DELETE FROM `StoryBookings` WHERE `booking_id` = :booking_id;");

        $stmt->bindValue('booking_id', $booking_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets JazzBooking by id.
     * @param int $booking_id id of the booking.
     * @return ?JazzBooking returns booking if it was found, otherwise, returns null. 
     */
    public function getJazzBookingById(int $booking_id) : ?JazzBooking {
        $stmt = $this->connection->prepare("SELECT `booking_id`, `performer_id`, `amount` FROM `JazzBookings` WHERE `booking_id` = :booking_id;");

        $stmt->execute(['booking_id' => $booking_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, JazzBooking::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

     /**
     * Creates a jazz booking in the db.
     * @param JazzBooking $booking booking you want to create
     * @return ?int returns id of new booking if operation was successfull, otherwise null.
     */
    public function createJazzBooking(JazzBooking $booking) : ?int {
        $sql = "INSERT INTO `JazzBookings`(`performer_id`, `amount`) VALUES (:performer_id, :amount);";     

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('performer_id', $booking->performer_id, PDO::PARAM_INT);
        $stmt->bindValue('amount', $booking->amount, PDO::PARAM_INT);

        $res = $stmt->execute();     

        if($res == false) return null; 

        return $this->connection->lastInsertId();
    }

    /**
     * Removes JazzBooking by its id.
     * @param int $booking_id id of the booking.
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    public function removeJazzBooking(int $booking_id) : bool {
        $stmt = $this->connection->prepare("DELETE FROM `JazzBookings` WHERE `booking_id` = :booking_id;");

        $stmt->bindValue('booking_id', $booking_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Returns total number of non-cart orders for orders cms.
     * @return int|bool if error during query excetution, returns false. Otherwise returns total order number.
     */
    public function getTotalOrderNumberForCms() : int|bool{
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM `Orders` WHERE `status` != 0;");

        $res = $stmt->execute();  
        
        $res = $stmt->fetch(PDO::FETCH_BOTH);

        return $res === false ? false : $res[0];
    }

    /**
     * Returns paginated and sorted array of order for cms page.
     * @param int $orders_per_page number of orders per page.
     * @param int $page current page.
     * @param string $sort name of field to sort by.
     * @param int $sort_order order of sorting (either 0 for ASC or 1 for DESC).
     * @return array|null|bool returns false if there were ant errors during query execution. Returns null if no orders found. Otherwise, returns array of orders.
     */
    public function getOrdersSortedForCms(int $orders_per_page, int $page, int $sort, int $sort_order) : array|null|bool {
        $sql = "SELECT `order_id`, `user_id`, `date` AS `date_`, `status` AS `status_`, `total_price` FROM `Orders` WHERE `status` != 0 ORDER BY ";
        $sql .= $this->getSortStringCms($sort, $sort_order);
        $sql .= ' LIMIT :limit OFFSET :offset;';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('limit', $orders_per_page, PDO::PARAM_INT);
        $stmt->bindValue('offset', $orders_per_page * ($page - 1), PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
        return $stmt->fetchAll();
    }

    private function getSortStringCms(string $sort, int $sort_order) : string {
        $order_string = $sort_order === 0 ? 'ASC' : 'DESC';

        switch($sort){
            case 0:
                return '`date`' . $order_string; 
            case 1:
                return '`status`' . $order_string; 
            case 2:
                return '`total_price`' . $order_string; 
            default:
                return '`date`' . $order_string;
        }
    }

    /**
     * returns order by id for cms (no cart orders)
     * @param int $order_id id of searched order.
     */
    public function getOrderByIdForCms(int $order_id) : ?Order {
        $stmt = $this->connection->prepare("SELECT `order_id`, `user_id`, `date` AS `date_`, `status` AS `status_`, `total_price`, `stripe_session` FROM `Orders` WHERE `order_id` = :order_id AND `status` != 0;");

        $stmt->bindValue('order_id', $order_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
        $res = $stmt->fetch();

        return $res === false ? null : $res;
    }

    /**
     * Returns arrray of export orders. 
     * @param OrderCmsExportParam $param export paramethers.
     * @return ExportOrder[]|null|bool returns false if there were any errors during query execution.
     *  Returns null if no orders found.
     *  Returns array of export orders if query executed successfully.
     */
    public function getOrdersForExport(OrderCmsExportParam $param) : array|null|bool {
        $sql = $this->genQueryForExport($param);

        $stmt = $this->connection->prepare($sql);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, ExportOrder::class);
        $res = $stmt->fetchAll();

        return $res;
    }

    /**
     * Generetes sql query for exporting orders based on paramethers.
     * @param OrderCmsExportParam $param export paramethers (have to be set).
     * @return string sql query.
     */
    private function genQueryForExport(OrderCmsExportParam $param) : string {
        $sql = "SELECT ";

        $order_fields = $param->getQueryOrderArguments();
        $user_fields = $param->getQueryUserArguments();

        if(count($order_fields) > 0){
            $sql .= '`O`.`' . $order_fields[0] . '`';

            for($i = 1; $i < count($order_fields); $i++){
                $sql .= ', `O`.`' . $order_fields[$i] . '`';
            }

            for($i = 0; $i < count($user_fields); $i++){
                $sql .= ', `U`.`' . $user_fields[$i] . '`';
            }
        }
        else{
            $sql .= '`U`.`' . $user_fields[0] . '`';

            for($i = 1; $i < count($user_fields); $i++){
                $sql .= ', `U`.`' . $user_fields[$i] . '`';
            }
        }     
        
        if(count($user_fields) > 0){ 
            $sql .= ' FROM `Orders` AS `O` INNER JOIN `User` AS `U` ON `O`.`user_id` = `U`.`user_id` WHERE `status` != 0';
        }
        else{
            $sql .= ' FROM `Orders` AS `O` WHERE `status` != 0';
        }   

        $excluded_statuses = $param->getQueryExcludedStatuses();

        for ($i = 0; $i < count($excluded_statuses); $i++) { 
            $sql .= ' AND `status` != ' . $excluded_statuses[$i];
        }

        $sql .= ';';

        return $sql;
    }
}