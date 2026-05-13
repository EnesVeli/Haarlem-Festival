<?php

namespace App\Services;

use App\Enums\BookingType;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\History\HistoryBooking;
use App\Models\IBooking;
use App\Models\Jazz\JazzBooking;
use App\Models\Order;
use App\Models\StoryBooking;
use App\Models\YummyBooking;
use App\Repositories\HistoryCmsRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\JazzRepository;
use App\Repositories\OrderRepository;
use App\Repositories\StoriesRepository;
use App\Repositories\UserRepository;
use App\Repositories\YummyRestaurantsRepository;

class OrderCmsService {
    private static ?OrderCmsService $_instance = null;

    public static function getInstance() : OrderCmsService {
        if(self::$_instance === null) self::$_instance = new OrderCmsService(UserRepository::getInstance(), OrderRepository::getInstance(),
            HistoryCmsRepository::getInstance(), YummyRestaurantsRepository::getInstance(), StoriesRepository::getInstance(), HistoryRepository::getInstance(),
            JazzRepository::getInstance());

        return self::$_instance;
    }

    private UserRepository $user_rep;
    private OrderRepository $order_rep;
    private HistoryCmsRepository $history_cms_rep;
    private YummyRestaurantsRepository $restaurant_rep;
    private StoriesRepository $story_rep;
    private HistoryRepository $history_rep;
    private JazzRepository $jazz_rep;

    private function __construct(UserRepository $user_rep, OrderRepository $order_rep, HistoryCmsRepository $history_cms_rep,
        YummyRestaurantsRepository $restaurant_rep, StoriesRepository $story_rep, HistoryRepository $history_rep, JazzRepository $jazz_rep)
    {
        $this->user_rep = $user_rep;
        $this->order_rep = $order_rep;
        $this->history_cms_rep = $history_cms_rep;
        $this->restaurant_rep = $restaurant_rep;
        $this->story_rep = $story_rep;
        $this->history_rep = $history_rep;
        $this->jazz_rep = $jazz_rep;
    }

    public function getTotalOrderNumberForCms() : int|bool {
        return $this->order_rep->getTotalOrderNumberForCms();
    }

    public function getOrdersSortedForCms(int $orders_per_page, int $page, int $sort, int $sort_order) : array|null|bool {
        return $this->order_rep->getOrdersSortedForCms($orders_per_page, $page, $sort, $sort_order);
    }

    public function getOrderForView(int $id) : ?Order {
        $order = $this->order_rep->getOrderByIdForCms($id);
        if($order === null) return null;

        $this->fillInOrder($order);

        return $order;
    }

    
    /**
     * Fills order's order_items and fills every order_item with bookings (from db). If order is null, then nogthing is filled.
     * @param ?Order $order order to be filed
     * @return void
     */
    private function fillInOrder(?Order $order){
        if($order !== null){
            $order_items = $this->order_rep->getOrderOrderItems($order->order_id);
            if($order_items === false) throw new QueryExecutionException("Failed to get order order_items.");  

            if($order_items === null) $order_items = [];
            
            foreach($order_items as $item){
                $booking = $this->getBookingByIdAndType($item->booking_id, $item->booking_type);
                if($booking === null) throw new QueryExecutionException("Failed to get booking.");  

                $item->booking = $booking;
            }

            $order->order_items = $order_items;
        }
    }

    /**
     * Gets booking by id and fills it with needed data depending on the type.
     * @param int $booking_id id of the booking.
     * @param BookingType $booking_type type of the booking.
     * @throws QueryExecutionException if any errors during query execution.
     * @return HistoryBooking|JazzBooking|StoryBooking|YummyBooking|null If booking type is not listed, returns null. Otherwise, returns booking.
     */
    public function getBookingByIdAndType(int $booking_id, BookingType $booking_type) : ?IBooking{
        switch($booking_type){
            case BookingType::Yummy:
                $book = $this->order_rep->getYummyBookingById($booking_id);

                if($book != null){
                    $book->reservation_time_slot = $this->restaurant_rep->getRestaurantTimeSlotById($book->reservation_id);
                    if($book->reservation_time_slot == null) throw new QueryExecutionException("Failed to get reservation time slot for yummy booking.");  

                    $book->restaurant = $this->restaurant_rep->getRestaurantById($book->reservation_time_slot->restaurant_id);
                    if($book->restaurant == null) throw new QueryExecutionException("Failed to get restaurant for yummy booking.");  
                }
                return $book;
            case BookingType::History:
                $book = $this->order_rep->getHistoryBookingById($booking_id);

                if($book != null){
                    $book->reservation = $this->history_rep->getHistoryReservationSlotById($book->reservation_id);
                    if($book->reservation == null) throw new QueryExecutionException("Failed to get reservation for history booking.");  

                    $book->time_slot = $this->history_rep->getHistoryTimeSlotById($book->reservation->slot_id);
                    if($book->reservation == null) throw new QueryExecutionException("Failed to get time slot for history booking.");  
                }
                return $book;
            case BookingType::Stories:
                $book = $this->order_rep->getStoryBookingById($booking_id);

                if($book != null){
                    $book->event = $this->story_rep->getById($book->event_id);
                    if($book->event == null) throw new QueryExecutionException("Failed to get story even for story booking.");   
                }

                return $book;
            case BookingType::Jazz:
                $book = $this->order_rep->getJazzBookingById($booking_id);

                $book->performer = $this->jazz_rep->getPerformerById($book->performer_id);
                if($book->performer == null) throw new QueryExecutionException("Failed to get jazz performer.");

                return $book;
        }

        return null;
    }
}