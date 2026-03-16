<?php
namespace App\Services;

use App\Repositories\StoriesRepository;
use App\Models\StoryEvent;

class StoriesService
{
    private StoriesRepository $repository;

    public function __construct()
    {
        $this->repository = new StoriesRepository();
    }

    // Returns all story events (used on the homepage)
    public function getAllEvents(): array
    {
        return $this->repository->getAll();
    }

    // Returns one event by slug (used on the detail page)
    // Returns null if the slug doesn't exist
    public function getEventBySlug(string $slug): ?StoryEvent
    {
        return $this->repository->getBySlug($slug);
    }
}
