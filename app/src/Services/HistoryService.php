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
    private const MAX_DATE_OFFSET = 31;

    private static ?HistoryService $_instance = null;

    public static function getInstance(): HistoryService {
        if(self::$_instance === null) self::$_instance = new HistoryService(HistoryRepository::getInstance(), OrderRepository::getInstance(), OrderService::getInstance());

        return self::$_instance;
    }

    private HistoryRepository $historyRepository;
    private OrderRepository $orderRepository;
    private OrderService $orderService;

    private function __construct(HistoryRepository $historyRepository, OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->historyRepository = $historyRepository;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    public static function getMaxDateOffset(): int
    {
        return self::MAX_DATE_OFFSET;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHighlights(): array
    {
        return $this->historyRepository->getAllHighlightsWithSlugs();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTickets(): array
    {
        return $this->historyRepository->getAvailableTickets();
    }

    /**
     * @return array<string, mixed>
     */
    public function getContent(): array
    {
        return $this->historyRepository->getAllContent();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getContentBySection(string $section): ?array
    {
        return $this->historyRepository->getContentBySection($section);
    }

    /**
     * @return array<string, array<string, mixed>>|null
     */
    public function getDetailPage(string $slug): ?array
    {
        $detail = $this->historyRepository->getDetailBySlug($slug);

        if (!$detail) {
            return null;
        }

        return [
            'detail'   => $detail,
            'sections' => $this->historyRepository->getDetailSections($detail['id']),
            'gallery'  => $this->historyRepository->getDetailGallery($detail['id']),
            'facts'    => $this->historyRepository->getDetailFacts($detail['id']),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getOtherHighlights(string $currentSlug, int $limit = 2): array
    {
        $allHighlights = $this->historyRepository->getAllHighlightsWithSlugs();

        $others = array_filter($allHighlights, function ($h) use ($currentSlug) {
            return $h['slug'] !== $currentSlug && !empty($h['slug']);
        });

        return array_slice($others, 0, $limit);
    }

    public function getHistoryReservationSlotById(int $reservationId): ?HistoryReservationSlot
    {
        return $this->historyRepository->getHistoryReservationSlotById($reservationId);
    }

    public function getHistoryTimeSlotById(int $slotId): ?HistoryTimeSlot
    {
        return $this->historyRepository->getHistoryTimeSlotById($slotId);
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    public function getAllTimeSlots(): ?array
    {
        return $this->historyRepository->getAllTimeSlots();
    }

    /**
     * Get or create a reservation for the given slot and date offset.
     * Note: This method has a side effect — it creates a reservation if one doesn't exist.
     */
    public function getOrCreateReservation(int $slotId, int $dateOffset): HistoryReservationSlot
    {
        if($dateOffset < 0 || $dateOffset > self::MAX_DATE_OFFSET) {
            throw new \InvalidArgumentException("Date offset must be between 0 and " . self::MAX_DATE_OFFSET);
        }

        $reservation = $this->historyRepository->getHistoryReservationBySlotIdAndDateOffset($slotId, $dateOffset);
        if($reservation !== null) {
            return $reservation;
        }

        $newReservationId = $this->historyRepository->addHistoryReservationBySlotIdAndDateOffset($slotId, $dateOffset);
        if($newReservationId === null) {
            throw new QueryExecutionException("Failed to add history reservation.");
        }

        $reservation = $this->historyRepository->getHistoryReservationSlotById($newReservationId);
        if($reservation === null) {
            throw new QueryExecutionException("Failed to retrieve newly created reservation.");
        }

        return $reservation;
    }

    /**
     * Book a history tour for a user.
     * @param HistoryBooking $booking booking that will be created (except booking_id and date).
     * @param int $userId id of currently logged in user.
     * @throws QueryExecutionException thrown when there were errors during query execution.
     * @throws OverBookingException thrown when booked space exceeds available.
     * @throws \InvalidArgumentException thrown when booking data is invalid.
     */
    public function bookHistoryBooking(HistoryBooking $booking, int $userId): void
    {
        // Get reservation
        $reservation = $this->historyRepository->getHistoryReservationSlotById($booking->reservation_id);
        if($reservation === null) {
            throw new QueryExecutionException("Reservation not found.");
        }
        
        // Get time slot
        $timeSlot = $this->historyRepository->getHistoryTimeSlotById($reservation->slot_id);
        if($timeSlot === null) {
            throw new QueryExecutionException("Time slot not found.");
        }

        // Check if there are enough spaces for booking
        $requiredSpaces = $booking->family_count * 4 + $booking->individual_count;
        if($reservation->booked + $requiredSpaces > $timeSlot->capacity) {
            throw new OverBookingException("Not enough available spaces for this booking.");
        }

        // Combine date from reservation and time from time slot
        $date = clone $reservation->date;
        $date->setTime($timeSlot->time->format('H'), $timeSlot->time->format('i'), $timeSlot->time->format('s'));
        $booking->date = $date;

        // Create booking in db, and add it to cart
        $this->orderService->createAndAddBookingToCart($userId, $booking);
    }

    public function getIndividualPrice(): int
    {
        return $this->orderService->getHistoryIndividualPrice();
    }

    public function getFamilyPrice(): int
    {
        return $this->orderService->getHistoryFamilyPrice();
    }
}