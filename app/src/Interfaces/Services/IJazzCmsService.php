<?php

namespace App\Interfaces\Services;

interface IJazzCmsService
{
    public function getDashboardData(): array;

    public function getHeroPageData(): array;
    public function updateHero(array $data, ?array $file): void;

    public function getIntroPageData(): array;
    public function updateIntro(array $data): void;

    public function getExperiencesPageData(): array;
    public function getExperienceByIdData(int $id): array;
    public function storeExperience(array $data, ?array $file): void;
    public function updateExperience(array $data, ?array $file): void;
    public function deleteExperience(int $id): void;

    public function getPerformersPageData(): array;
    public function getPerformerByIdData(int $id): array;
    public function storePerformer(array $data, ?array $heroFile, ?array $imageFile): void;
    public function updatePerformer(array $data, ?array $heroFile, ?array $imageFile): void;
    public function deletePerformer(int $id): void;

    public function getRecommendationsPageData(): array;
    public function getRecommendationByIdData(int $id): array;
    public function storeRecommendation(array $data, ?array $file): void;
    public function updateRecommendation(array $data, ?array $file): void;
    public function deleteRecommendation(int $id): void;

    public function getLocationsPageData(): array;
    public function getLocationByIdData(int $id): array;
    public function storeLocation(array $data): void;
    public function updateLocation(array $data): void;
    public function deleteLocation(int $id): void;
}
