<?php

namespace App\Services\Jazz;
use App\Interfaces\Repositories\IJazzRepository;
use App\Interfaces\Services\IJazzService;
use App\Repositories\JazzRepository;
use RuntimeException;
use Throwable;

class JazzService implements IJazzService
{
    private IJazzRepository $jazzRepo;

    public function __construct(?IJazzRepository $jazzRepo = null)
    {
        $this->jazzRepo = $jazzRepo ?? new JazzRepository();
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
            die($e->getMessage()); 
        }
    }
    
}