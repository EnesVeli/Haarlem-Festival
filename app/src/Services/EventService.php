<?php
namespace App\Services;

use App\Repositories\EventRepository;

class EventService
{
    private EventRepository $repository;

    public function __construct()
    {
        $this->repository = new EventRepository();
    }

    public function getHomepageEvents(): array
    {
        return $this->repository->getHomepageEvents();
    }
}
