<?php
namespace App\Services;

use App\Repositories\HomeRepository;

class HomeService
{
    private static ?HomeService $_instance = null;

    public static function getInstance() : HomeService {
        if(self::$_instance === null) self::$_instance = new HomeService(HomeRepository::getInstance());

        return self::$_instance;
    }

    private HomeRepository $repository;

    private function __construct(HomeRepository $repository)
    {
        $this->repository = $repository;
    }

    // ─── READ ────────────────────────────────────────────────────────────────

    public function getHomeContent(): array
    {
        return $this->repository->getHomeContent();
    }

    public function getHomeEvents(): array
    {
        return $this->repository->getHomeEvents();
    }

    /** Returns ALL event cards (including inactive) for the CMS. */
    public function getAllHomeEvents(): array
    {
        return $this->repository->getAllHomeEvents();
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

   

    public function saveHomeContent(array $data): void
    {
        $this->repository->saveHomeContent($data);
    }

    public function saveHomeEvent(?int $id, array $data): void
    {
        $this->repository->saveHomeEvent($id, $data);
    }

    public function deleteHomeEvent(int $id): void
    {
        $this->repository->deleteHomeEvent($id);
    }
}