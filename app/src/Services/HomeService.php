<?php
namespace App\Services;

use App\Repositories\HomeRepository;

class HomeService
{
    private HomeRepository $repository;

    public function __construct()
    {
        $this->repository = new HomeRepository();
    }

    public function getHomeContent(): array
    {
        return $this->repository->getHomeContent();
    }

    public function getHomeEvents(): array
    {
        return $this->repository->getHomeEvents();
    }

    public function getVenueList(): array
    {
        return [
            'Patronaat Haarlem', 'Grand Cafe Brinkman', 'New Vegas',
            'Ratatouille', 'Restaurant ML', 'Urban Frenchy Bistro', 'Restaurant Fris',
            'Grote Markt', 'Corrie ten Boom house',
            'Verhalenhuis Haarlem', 'Elswout Theater', 'De Schuur', 'Café de Roemer',
        ];
    }
}