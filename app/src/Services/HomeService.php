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

    /**
     * @return array<string, string>
     */
    public function getHomeContent(): array
    {
        return $this->repository->getHomeContent();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHomeEvents(): array
    {
        return $this->repository->getHomeEvents();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllHomeEvents(): array
    {
        return $this->repository->getAllHomeEvents();
    }

    /**
     * @param array<string, string> $data
     */
    public function saveHomeContent(array $data): void
    {
        $this->repository->saveHomeContent($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function saveHomeEvent(?int $id, array $data): void
    {
        $this->repository->saveHomeEvent($id, $data);
    }

    public function deleteHomeEvent(int $id): void
    {
        $this->repository->deleteHomeEvent($id);
    }
}
