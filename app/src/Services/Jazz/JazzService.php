<?php

namespace App\Services\Jazz;
use App\Interfaces\Repositories\IJazzRepository;
use App\Interfaces\Services\IJazzService;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\Jazz\JazzBooking;
use App\Models\Jazz\JazzPerformer;
use App\Repositories\JazzRepository;
use App\Services\OrderService;
use RuntimeException;
use Throwable;

class JazzService implements IJazzService
{
    private static ?JazzService $_instance = null;

    public static function getInstance() : JazzService {
        if(self::$_instance === null) self::$_instance = new JazzService(JazzRepository::getInstance(), OrderService::getInstance());

        return self::$_instance;
    }

    private IJazzRepository $jazzRepo;
    private OrderService $order_service;

    private function __construct(IJazzRepository $jazzRepo, OrderService $order_service)
    {
        $this->jazzRepo = $jazzRepo;

        $this->order_service = $order_service;
    }

    public function getHomePageData(): array
    {
        try {
            return [
                'hero' => $this->jazzRepo->getHero(),
                'intro' => $this->jazzRepo->getIntro(),
                'experiences' => $this->jazzRepo->getExperiences(),
                'performers' => $this->jazzRepo->getAllPerformers(true),
                'recommendations' => $this->jazzRepo->getRecommendations(),
                'locations' => $this->jazzRepo->getLocations(),
            ];
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to load Jazz home page data.', 0, $e);
        }
    }

    public function getPerformerDetail(int $id): ?array
    {
        try {
            $performer = $this->jazzRepo->getPerformerById($id);
    
            if (!$performer) {
                return null;
            }
    
            return [
                'performer' => $performer,
                'appearances' => $this->jazzRepo->getAppearancesByPerformer($id),
                'highlights' => $this->jazzRepo->getHighlightsByPerformer($id),
                'tracks' => $this->jazzRepo->getTracksByPerformer($id),
                'locations' => $this->jazzRepo->getLocationsByPerformer($id),
                'recommendations' => $this->jazzRepo->getRecommendations(),
            ];
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to load performer data.', 0, $e);
        }
    }
    
    public function getPerformerById(int $id) : ?JazzPerformer {
        return $this->jazzRepo->getPerformerById($id);
    }

    public function bookTickets(int $performer_id, int $quantity, int $user_id): void {
        $perf = $this->jazzRepo->getPerformerById($performer_id);
        if($perf === null) throw new QueryExecutionException("Failed to get performer by its id");

        $booking = new JazzBooking();
        $booking->performer_id = $performer_id;
        $booking->amount = $quantity;

        $this->order_service->createAndAddBookingToCart($user_id, $booking);
    }

    public function getActivePerformersForTickets(int $page, int $perf_per_page) : array|null|bool {
        return $this->jazzRepo->getActivePerformersForTickets($page, $perf_per_page);
    }

     public function getNumberOfActivePerformers() : int|bool {
        return $this->jazzRepo->getNumberOfActivePerformers();
    }
}