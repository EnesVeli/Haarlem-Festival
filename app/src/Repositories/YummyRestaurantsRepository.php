<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\Dish;
use App\Models\Restaurant;
use App\Models\RestaurantBooking;
use App\Models\RestaurantImage;
use App\Models\RestaurantTimeSlot;
use PDO;

enum RestaurantSortingOption : int{
    case NameASC = 0;
    case NameDESC = 1;
    case RatingASC = 2;
    case RatingDESC = 3;
    case CostASC = 4;
    case CostDESC = 5;
}

class YummyRestaurantsRepository extends Repository
{
    public const NUMBER_OF_RESTAURANTS_PER_PAGE = 20;
    public const NUMBER_OF_RESTAURANTS_PER_PAGE_CMS = 24;

    /**
     * @param int $restaurant_id id of searched restaurant.
     * @return ?Restaurant returns restaurant object if found, null if not.
     */
    public function getRestaurantById(string $restaurant_id): ?Restaurant
    {
        $stmt = $this->connection->prepare("SELECT `restaurant_id`, `main_img_path`, `name`, `mini_text`, `rating`, `cost_rating`, `active`, `text`, `opening_hours`, `address_text`, `address_uri`, `website_link` FROM `YummyRestaurants` WHERE `restaurant_id` = :restaurant_id AND `active` = 1");
        $stmt->execute(['restaurant_id' => $restaurant_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);
        $res = $stmt->fetch(); 

        return $res == false ? null : $res;
    }

    /**
     * @return ?array returns 8 or less active restaurant sorted by popularity as list of objects, or null if something went wrong.
     */
    public function getTopActiveRestaurants() : ?array {
        $stmt = $this->connection->prepare("SELECT `restaurant_id`, `main_img_path`, `name`, `mini_text`, `rating`, `cost_rating`, `active` FROM `YummyRestaurants` WHERE `active` = 1 LIMIT 8");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);
        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }   

    /**
     * @param array $all_types Array with all selected filters. If empty, no filter is applied.
     * @param int $page Selected resturant list page (e.g. if page is 0 will return resturants 0-24, if page is 1 will return 25-48 retaurants, e.t.c)
     * @param RestaurantSortingOption $sorting Sorting method applied. If empty, sorts by name ascending
     *
     * @return array array with two elements. 
     * [0] is total number of restaurants that fit filter.
     * [1] is limeted, sorted and optinaly filtered list of restaurants. 
     * Both of the elements can be null.
     */
    public function getFilteredRestaurants($all_types, $page, RestaurantSortingOption $sorting = RestaurantSortingOption::NameASC) : array {    
        if($all_types == null || count($all_types) == 0){
            $filter = null;
        }      
        else{
            $filter = 'WHERE `name` = :t0';
            $param = ['t0' => $all_types[0]];

            $count = count($all_types);

            for ($i = 1; $i < $count; $i++) { 
                $filter = $filter . ' OR `name` = :t' . $i;
                $param["t$i"] = $all_types[$i];
            }

            //echo '<pre>'; print_r($param); echo '</pre>';
        }      

        $sort_string = $this->getSortString($sorting);

        $limit = YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE;
        $offset = YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE * $page;

        if($filter == null){
            $query = "SELECT `R`.`restaurant_id`, `R`.`main_img_path`, `R`.`name`, `R`.`mini_text`, `R`.`rating`, `R`.`cost_rating`, `R`.`active` FROM `YummyRestaurants` AS `R` WHERE `R`.`active` = 1 $sort_string LIMIT $limit OFFSET $offset";

            $count_query = "SELECT COUNT(*) FROM `YummyRestaurants` AS `R` WHERE `R`.`active` = 1";
        }
        else{
            $query =   "SELECT `R`.`restaurant_id`, `R`.`main_img_path`, `R`.`name`, `R`.`mini_text`, `R`.`rating`, `R`.`cost_rating`, `R`.`active`
                        FROM `YummyRestaurants` AS `R`                 
                        INNER JOIN
                            (SELECT `RFT`.`restaurant_id`, `RFT`.`type_id`, COUNT(*)
                            FROM `YummyRestaurantFoodTypes` AS `RFT` 
                            INNER JOIN
                                (SELECT `type_id`, `name` 
                                FROM `YummyFoodTypes` 
                                $filter) AS `T` 
                            ON `RFT`.`type_id` = `T`.`type_id`
                            GROUP BY `RFT`.`restaurant_id`
                            HAVING COUNT(*) >= $count) AS `RT`
                        ON `RT`.`restaurant_id` = `R`.`restaurant_id`
                        WHERE `R`.`active` = 1
                        $sort_string
                        LIMIT $limit OFFSET $offset";

            $count_query = "SELECT COUNT(*)
                        FROM `YummyRestaurants` AS `R`                 
                        INNER JOIN
                            (SELECT `RFT`.`restaurant_id`, `RFT`.`type_id`, COUNT(*)
                            FROM `YummyRestaurantFoodTypes` AS `RFT` 
                            INNER JOIN
                                (SELECT `type_id`, `name` 
                                FROM `YummyFoodTypes` 
                                $filter) AS `T` 
                            ON `RFT`.`type_id` = `T`.`type_id`
                            GROUP BY `RFT`.`restaurant_id`
                            HAVING COUNT(*) >= $count) AS `RT`
                        ON `RT`.`restaurant_id` = `R`.`restaurant_id`
                        WHERE `R`.`active` = 1";
        }

        $output = [];
        
        // Get total number of restaurants that fit filter
        $stmt_count = $this->connection->prepare($count_query);

        if($filter == null) $stmt_count->execute();
        else $stmt_count->execute($param);

        $res_number = $stmt_count->fetch(PDO::FETCH_BOTH);

        array_push($output, $res_number == false ? 0 : $res_number[0]);


        // Get list of restaurants limited, filtered and sorted
        $stmt = $this->connection->prepare($query);

        if($filter == null) $stmt->execute();
        else $stmt->execute($param);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);

        array_push($output, $stmt->fetchAll());
       
        
        // Return limited list and total count
        return $output;
    }

    /**
     * @param RestaurantSortingOption $sorting desired type of sorting
     * @return string returns sql associated with selected sorting type. If unknown sorting type is passed, returns empty string.
     */
    private function getSortString(RestaurantSortingOption $sorting) : string {
        switch($sorting){
            case RestaurantSortingOption::NameASC:
                return "ORDER BY `R`.`name` ASC";
            case RestaurantSortingOption::NameDESC:
                return "ORDER BY `R`.`name` DESC";
            case RestaurantSortingOption::RatingASC:
                return "ORDER BY `R`.`rating` ASC";
            case RestaurantSortingOption::RatingDESC:
                return "ORDER BY `R`.`rating` DESC";
            case RestaurantSortingOption::CostASC:
                return "ORDER BY `R`.`cost_rating` ASC, `R`.`name` ASC";
            case RestaurantSortingOption::CostDESC:
                return "ORDER BY `R`.`cost_rating` DESC, `R`.`name` DESC";
            default:
                return "";
        }
    }

    /**
     * @param int $restaurant_id id of searched restaurant.
     * @return ?array returns array of restaurant images, returns null, if nothing were found.
     */
    public function getRestaurantImages(int $restaurant_id) : ?array {
        $stmt = $this->connection->prepare("SELECT `image_id`, `restaurant_id`, `path` FROM `YummyRestaurantImages` WHERE `restaurant_id` = :restaurant_id LIMIT 11");
        $stmt->execute(['restaurant_id' => $restaurant_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, RestaurantImage::class);
        $res = $stmt->fetchAll(); 

        return $res == false ? null : $res;
    }

    /**
     * @param int $restaurant_id id of searched restaurant.
     * @return ?array returns array of restaurant dishes, returns null, if nothing were found.
     */
    public function getRestaurantDishes(int $restaurant_id) : ?array {
        $stmt = $this->connection->prepare("SELECT `dish_id`, `restaurant_id`, `name`, `text`, `image_path` FROM `YummyDishes` WHERE `restaurant_id` = :restaurant_id LIMIT 8");
        $stmt->execute(['restaurant_id' => $restaurant_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Dish::class);
        $res = $stmt->fetchAll(); 

        return $res == false ? null : $res;
    }

    /**
     * @param int $restaurant_id id of searched restaurant.
     * @param int $date_offset offset in day from today.
     * @return ?array returns array of restaurant time slots (joined YummyRestaurantTimeSlots and YummyReservationSlots) in range from today to two weeks from now, returns null, if nothing were found.
     */
    public function getRestaurantTimeSlots(int $restaurant_id, int $date_offset) : ?array {
        $sql = "SELECT `R`.`reservation_id`,`T`.`slot_id`, `T`.`restaurant_id`, `T`.`time` AS `time_`, `R`.`date` AS `date_`, `T`.`capacity`, `R`.`booked`
                FROM `YummyRestaurantTimeSlots` AS `T` 
                INNER JOIN
                    (SELECT * 
                     FROM `YummyReservationSlots`) AS `R`
                ON `T`.`slot_id` = `R`.`slot_id`
                WHERE `T`.`restaurant_id` = :restaurant_id AND `date` = DATE(NOW()) + INTERVAL +:date_offset DAY;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['restaurant_id' => $restaurant_id,
                        'date_offset' => $date_offset]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, RestaurantTimeSlot::class);
        $res = $stmt->fetchAll(); 

        return $res == false ? null : $res;
    }

    /**
     * @param int $slot_id id of searched time slot.
     * @param int $date_offset offset in day from today.
     * @return ?RestaurantTimeSlot returns a time slot (joined YummyRestaurantTimeSlots and YummyReservationSlots) by slot id and at selected date, returns null, if nothing were found.
     */
    public function getRestaurantTimeSlotById(int $slot_id, int $date_offset) : ?RestaurantTimeSlot {
        $sql = "SELECT `R`.`reservation_id`,`T`.`slot_id`, `T`.`restaurant_id`, `T`.`time` AS `time_`, `R`.`date` AS `date_`, `T`.`capacity`, `R`.`booked`
                FROM `YummyRestaurantTimeSlots` AS `T` 
                INNER JOIN
                    (SELECT * 
                     FROM `YummyReservationSlots`) AS `R`
                ON `T`.`slot_id` = `R`.`slot_id`
                WHERE `T`.`slot_id` = :slot_id AND `date` = DATE(NOW()) + INTERVAL +:date_offset DAY;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['slot_id' => $slot_id,
                        'date_offset' => $date_offset]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, RestaurantTimeSlot::class);
        $res = $stmt->fetch(); 

        return $res == false ? null : $res;
    }

    /**
     * Method adds seat_number to number of bookings of a time slot with selected slot_id and date.
     * @param int $slot_id id of searched time slot.
     * @param int $date_offset offset in day from today.
     * @param int $seat_number number of seats booked.
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function bookRestaurantTimeSlot(int $slot_id, int $date_offset, int $seat_number) : bool {
        $sql = "UPDATE `YummyReservationSlots` SET `booked` = `booked` + :seat_number
                WHERE `slot_id` = :slot_id AND `date` = DATE(NOW()) + INTERVAL +:date_offset DAY;";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(['slot_id' => $slot_id, 'date_offset' => $date_offset, 'seat_number' => $seat_number]);
    }

    /**
     * Method subtracts seat_number from number of bookings of a time slot with selected slot_id and date.
     * @param int $slot_id id of searched time slot.
     * @param int $date_offset offset in day from today.
     * @param int $seat_number number of seats booked.
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function unbookRestaurantTimeSlot(int $slot_id, int $date_offset, int $seat_number) : bool {
        $sql = "UPDATE `YummyReservationSlots` SET `booked` = `booked` - :seat_number
                WHERE `slot_id` = :slot_id AND `date` = DATE(NOW()) + INTERVAL +:date_offset DAY;";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(['slot_id' => $slot_id, 'date_offset' => $date_offset, 'seat_number' => $seat_number]);
    }

    /**
     * Creates a restaurant booking in the db.
     * @param RestaurantBooking $booking booking you want to create
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function createBooking(RestaurantBooking $booking) : bool {
        $sql = "INSERT INTO `YummyBookings`(`reservation_id`, `user_id`, `date`, `adult_number`, `child_number`, `comment`) 
                VALUES (:reservation_id, :user_id, :date, :adult_number , :child_number, :comment);";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(['reservation_id' => $booking->reservation_id,
                               'user_id' => $booking->user_id,
                               'date' => $booking->date,
                               'adult_number' => $booking->adult_number,
                               'child_number' => $booking->child_number,
                               'comment' => $booking->comment]);
    }

    /**
     * Creates a restaurant booking in the db.
     * @param RestaurantBooking $booking booking you want to create
     * @param int $date_offset offset in days from today (i. e. today + offset(number of days) will be put in date, insteaqd of $booking date value).
     * @return bool returns true if operation was successfull, otherwise false.
     */
    public function createBookingWithOffest(RestaurantBooking $booking, int $date_offset) : bool {
        $sql = "INSERT INTO `YummyBookings`(`reservation_id`, `user_id`, `date`, `adult_number`, `child_number`, `comment`) 
                VALUES (:reservation_id, :user_id, DATE(NOW()) + INTERVAL +:date_offset DAY, :adult_number , :child_number, :comment);";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(['reservation_id' => $booking->reservation_id,
                               'user_id' => $booking->user_id,
                               'date_offset' => $date_offset,
                               'adult_number' => $booking->adult_number,
                               'child_number' => $booking->child_number,
                               'comment' => $booking->comment]);
    }

    /**
     * Used to get list of resturants for cms some fields are null.
     * @param int $sort number representing one of the restaurant fields (e.g. 0 - name, 1 - mini_text, etc.).
     * @param int $order if 0 than ASC otherwise DESC.
     * @param int $page list page number (e.g. if page is 0 will return resturants 0-24, if page is 1 will return 25-48 retaurants, etc.)
     * @return ?array returns an array of restaurants. If there are any error returns null.
     */
    public function getRestaurantListCms(int $sort, int $order, int $page) : ?array {
        $limit = YummyRestaurantsRepository::NUMBER_OF_RESTAURANTS_PER_PAGE_CMS;
        $offset = $limit * $page;

        $sorting = $this->getSortFieldCMS($sort, $order == 0 ? 'ASC' : 'DESC');

        $sql = "SELECT `restaurant_id`, `main_img_path`, `name`, `mini_text`, `rating`, `cost_rating`, `active`
        FROM `YummyRestaurants`
        ORDER BY $sorting
        LIMIT $limit OFFSET $offset;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);
        $list = $stmt->fetchAll();

        return $list == false ? null : $list;
    }  

    private function getSortFieldCMS(int $sort, string $order){
        switch($sort){
            case 0:
                return '`name` ' . $order;
            case 1:
                return '`mini_text` ' . $order;
            case 2:
                return '`rating` ' . $order;
            case 3:
                return '`cost_rating` ' . $order . ', `name` ' . $order;
            case 4:
                return '`active` ' . $order . ', `name` ' . $order;
        }

        return '';
    }

    public function countAllRestaurants() : ?int {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM `YummyRestaurants`;");

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_DEFAULT);

        return $res == false ? null : $res[0];
    }
}