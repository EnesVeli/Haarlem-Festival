<?php

namespace App\Services;

use App\Repositories\JazzRepository;
use App\Repositories\EventRepository;

class JazzService
{
    private JazzRepository $repo;
    private EventRepository $eventRepo;

    public function __construct()
    {
        $this->repo = new JazzRepository();
        $this->eventRepo = new EventRepository();
    }

    public function getExperiences(): array
    {
        return $this->repo->getActiveExperiences();
    }

    public function getPerformers(): array
    {
        return $this->repo->getActivePerformers();
    }

    public function getEventRecommendationsForJazz(): array
    {
        return $this->eventRepo->getActiveRecommendationsByKeys(['yummy', 'history', 'stories']);
    }
}