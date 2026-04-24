<?php

namespace App\Services;

use App\Models\Exceptions\OverBookingException;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\History\HistoryBooking;
use App\Models\History\HistoryReservationSlot;
use App\Models\History\HistoryTimeSlot;
use App\Repositories\HistoryRepository;
use App\Repositories\OrderRepository;

class HistoryService
{
    public static int $max_date_offset = 12;

    private HistoryRepository $history_rep;
    private OrderRepository $order_rep;
    private OrderService $order_service;

    public function __construct()
    {
        $this->history_rep = new HistoryRepository();
        $this->order_rep = new OrderRepository();
        $this->order_service = new OrderService();
    }

    public function getHighlights()
    {
        return $this->history_rep->getAllHighlightsWithSlugs();
    }

    public function getTickets()
    {
        return $this->history_rep->getAvailableTickets();
    }

    public function getTicketPrices(): array
    {
        return $this->history_rep->getTicketPrices();
    }

    public function updateTicketPrice(string $type, float $price): void
    {
        $this->history_rep->updateTicketPrice($type, $price);
    }

    public function getContent(): array
    {
        return $this->history_rep->getAllContent();
    }

    public function getContentBySection($section)
    {
        return $this->history_rep->getContentBySection($section);
    }

    public function getDetailPage($slug)
    {
        $detail = $this->history_rep->getDetailBySlug($slug);

        if (!$detail) {
            return null;
        }

        return [
            'detail'   => $detail,
            'sections' => $this->history_rep->getDetailSections($detail['id']),
            'gallery'  => $this->history_rep->getDetailGallery($detail['id']),
            'facts'    => $this->history_rep->getDetailFacts($detail['id']),
        ];
    }

    public function getOtherHighlights($currentSlug, $limit = 2)
    {
        $allHighlights = $this->history_rep->getAllHighlightsWithSlugs();

        $others = array_filter($allHighlights, function ($h) use ($currentSlug) {
            return $h['slug'] !== $currentSlug && !empty($h['slug']);
        });

        return array_slice($others, 0, $limit);
    }

    public function getHistoryReservationSlotById(int $reservation_id) : ?HistoryReservationSlot{
        return $this->history_rep->getHistoryReservationSlotById($reservation_id);
    }

    public function getHistoryTimeSlotById(int $slot_id) : ?HistoryTimeSlot{
        return $this->history_rep->getHistoryTimeSlotById($slot_id);
    }

    public function getAllTimeSlots() : ?array {
        return $this->history_rep->getAllTimeSlots();
    }

    public function getHistoryReservationBySlotIdAndDateOffset(int $slot_id, int $date_offset) : ?HistoryReservationSlot {
        $res = $this->history_rep->getHistoryReservationBySlotIdAndDateOffset($slot_id, $date_offset);
        if($res != null) return $res;

        $new_reservation_id = $this->history_rep->addHistoryReservationBySlotIdAndDateOffset($slot_id, $date_offset);
        if($new_reservation_id == null) throw new QueryExecutionException("Failed to add history reservation.");

        return $this->history_rep->getHistoryReservationSlotById($new_reservation_id);
    }

    /**
     * Added booking to db and to cart.
     * @param HistoryBooking $booking booking that will be created (except booking_id and date).
     * @param int $user_id id of currently logged int user.
     * @throws QueryExecutionException thrown when there were errors during query execution.
     * @throws OverBookingException thrown when booked space exceeds avaliable.
     * @return void
     */
    public function bookHistoryBooking(HistoryBooking $booking, int $user_id) {
        // Get reservation
        $reserv = $this->history_rep->getHistoryReservationSlotById($booking->reservation_id);
        if($reserv == null) throw new QueryExecutionException();
        
        // Get time slot
        $time_slot = $this->history_rep->getHistoryTimeSlotById($reserv->slot_id);
        if($time_slot == null) throw new QueryExecutionException();

        // Check if there are enough space for booking
        if($reserv->booked + $booking->family_count * 4 + $booking->individual_count > $time_slot->capacity) throw new OverBookingException();

        // Put date into booking
        $date = clone $reserv->date; // Combine date from reservation and time from time slot
        $date->setTime($time_slot->time->format('H'), $time_slot->time->format('i'), $time_slot->time->format('s'));

        $booking->date = $date;

        // Create booking in db, and add it to cart
        $this->order_service->createAndAddBookingToCart($user_id, $booking);
    }

    public function getIndividualPrice() : int {
        return $this->order_service->getHistoryIndividualPrice();
    }

    public function getFamilyPrice() : int {
        return $this->order_service->getHistoryFamilyPrice();
    }
}