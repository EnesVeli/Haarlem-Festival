<?php

namespace App\Services\Jazz;

use App\Framework\Session;
use App\Repositories\JazzRepository;

class JazzCmsService
{
    private JazzRepository $jazzRepo;

    public function __construct()
    {
        $this->jazzRepo = new JazzRepository();
    }

    //dashboard

    public function getDashboardData(): array
    {
        return [
            'user' => Session::user(),
        ];
    }

    //hero

    public function getHeroPageData(): array
    {
        return [
            'user' => Session::user(),
            'hero' => $this->jazzRepo->getHero() ?? [],
        ];
    }

    public function updateHero(array $data): void
    {
        $this->jazzRepo->updateHero($data);
    }

   //intro

    public function getIntroPageData(): array
    {
        return [
            'user' => Session::user(),
            'intro' => $this->jazzRepo->getIntro() ?? [],
        ];
    }

    public function updateIntro(array $data): void
    {
        $this->jazzRepo->updateIntro($data);
    }

   //Experiences

    public function getExperiencesPageData(): array
    {
        return [
            'user' => Session::user(),
            'experiences' => $this->jazzRepo->getExperiences(),
        ];
    }

    public function getExperienceByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
            'experience' => $this->jazzRepo->getExperienceById($id) ?? [],
        ];
    }

    public function storeExperience(array $data): void
    {
        $this->jazzRepo->storeExperience($data);
    }

    public function updateExperience(array $data): void
    {
        $this->jazzRepo->updateExperience($data);
    }

    public function deleteExperience(int $id): void
    {
        $this->jazzRepo->deleteExperience($id);
    }

   //Performers

    public function getPerformersPageData(): array
    {
        return [
            'user' => Session::user(),
            'performers' => $this->jazzRepo->getAllPerformers(false),
        ];
    }

    public function getPerformerByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
            'performer' => $this->jazzRepo->getPerformerById($id) ?? [],
        ];
    }

    public function storePerformer(array $data): void
    {
        $this->jazzRepo->storePerformer($data);
    }

    public function updatePerformer(array $data): void
    {
        $this->jazzRepo->updatePerformer($data);
    }

    public function deletePerformer(int $id): void
    {
        $this->jazzRepo->deletePerformer($id);
    }

 //recommendations

    public function getRecommendationsPageData(): array
    {
        return [
            'user' => Session::user(),
            'recommendations' => $this->jazzRepo->getRecommendations(),
        ];
    }

    public function getRecommendationByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
            'recommendation' => $this->jazzRepo->getRecommendationById($id) ?? [],
        ];
    }

    public function storeRecommendation(array $data): void
    {
        $this->jazzRepo->storeRecommendation($data);
    }

    public function updateRecommendation(array $data): void
    {
        $this->jazzRepo->updateRecommendation($data);
    }

    public function deleteRecommendation(int $id): void
    {
        $this->jazzRepo->deleteRecommendation($id);
    }

   //locations

    public function getLocationsPageData(): array
    {
        return [
            'user' => Session::user(),
            'locations' => $this->jazzRepo->getLocations(),
        ];
    }

    public function getLocationByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
            'location' => $this->jazzRepo->getLocationById($id) ?? [],
        ];
    }

    public function storeLocation(array $data): void
    {
        $this->jazzRepo->storeLocation($data);
    }

    public function updateLocation(array $data): void
    {
        $this->jazzRepo->updateLocation($data);
    }

    public function deleteLocation(int $id): void
    {
        $this->jazzRepo->deleteLocation($id);
    }
}