<?php

namespace App\Services\Jazz;

use App\Repositories\JazzRepository;

class JazzService
{
    private JazzRepository $jazzRepo;

    public function __construct()
    {
        $this->jazzRepo = new JazzRepository();
    }

    public function getHomePageData(): array
    {
        return [
            'hero' => $this->jazzRepo->getHero(),
            'intro' => $this->jazzRepo->getIntro(),
            'experiences' => $this->jazzRepo->getExperiences(),
            'performers' => $this->jazzRepo->getAllPerformers(true),
            'recommendations' => $this->jazzRepo->getRecommendations(),
            'locations' => $this->jazzRepo->getLocations(),
        ];
    }

    public function getPerformerDetail(int $id): ?array
    {
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
    }
    
}