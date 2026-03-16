<?php
namespace App\Services;

use App\Repositories\StoriesRepository;
use App\Models\StoryEvent;

/**
 * StoriesService
 * Business-logic layer between controllers and the repository.
 */
class StoriesService
{
    private StoriesRepository $repository;

    public function __construct(StoriesRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return StoryEvent[] */
    public function getAllEvents(): array
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
    public function createEvent(array $data): bool
    {
        return $this->repository->insert($data);
    }

    /** Updates an existing story event. */
    public function updateEvent(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    /** Deletes a story event by ID. */
    public function deleteEvent(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /** Returns all ticket types for a given event. */
    public function getTicketTypesForEvent(int $eventId): array
    {
        return $this->repository->getTicketTypesForEvent($eventId);
    }

    /** Returns CMS homepage content for stories page. */
    public function getHomepageContent(): ?array
    {
        return $this->repository->getHomepageContent();
    }
}