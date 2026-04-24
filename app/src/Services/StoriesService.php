<?php
namespace App\Services;

use App\Models\Exceptions\PostMismatchException;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\StoryBooking;
use App\Repositories\StoriesRepository;
use App\Models\StoryEvent;

/**
 * StoriesService
 * Business-logic layer between controllers and the repository.
 */
class StoriesService
{
    private StoriesRepository $repository;
    private OrderService $order_service;

    public function __construct()
    {
        $this->repository = new StoriesRepository();
        $this->order_service = new OrderService();
    }

    /** @return StoryEvent[] */
    public function getAllEvents() : array
    {
        return $this->repository->getAll();
    }

    /** Finds a single event by its URL slug. */
    public function getEventBySlug(string $slug): ?StoryEvent
    {
        return $this->repository->getBySlug($slug);
    }

    /** Finds a single event by its primary key. */
    public function getEventById(int $id): ?StoryEvent
    {
        return $this->repository->getById($id);
    }

    /** Inserts a new story event. */
    public function createEvent(StoryEvent $event): int
    {
        $this->repository->insert($event);

        return 0;
    }

    /** Updates an existing story event. */
    public function updateEvent(StoryEvent $event): bool
    {
        return $this->repository->update($event);
    }

    /** Deletes a story event by ID. */
    public function deleteEvent(int $id): bool
    {
        return $this->repository->delete($id);
    }

     /** Returns all schedule sessions that share the same event name */
    public function getScheduleForEvent(string $name): array
    {
        return $this->repository->getScheduleByName($name);
    }

    public function createBooking(int $user_id, StoryBooking $booking){
        $event = $this->repository->getById($booking->event_id);
        if($event == null) throw new QueryExecutionException();

        if($booking->pay_as_you_like !== null && $event->is_pay_as_you_like == false) throw new PostMismatchException("Event is not pay as you like, but booking is.");

        if($booking->pay_as_you_like === null && $event->is_pay_as_you_like == true) throw new PostMismatchException("Event is pay as you like, but booking is not.");

        $this->order_service->createAndAddBookingToCart($user_id, $booking);
    }
}
