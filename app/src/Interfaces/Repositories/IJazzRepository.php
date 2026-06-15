<?php

namespace App\Interfaces\Repositories;

interface IJazzRepository
{
    public function getHero(): ?object;
    public function getHeroForCms(): ?object;
    public function updateHero(array $data): void;

    public function getIntro(): ?object;
    public function updateIntro(array $data): void;

    public function getExperiences(): array;
    public function getExperienceById(int $id): ?object;
    public function storeExperience(array $data): void;
    public function updateExperience(array $data): void;
    public function deleteExperience(int $id): void;

    public function getAllPerformers(bool $onlyActive = true): array;
    public function getPerformerById(int $id): ?object;
    public function storePerformer(array $data): void;
    public function updatePerformer(array $data): void;
    public function deletePerformer(int $id): void;
    public function getAppearancesByPerformer(int $performerId): array;
    public function getHighlightsByPerformer(int $performerId): array;
    public function getTracksByPerformer(int $performerId): array;
    public function updateHighlightsByPerformer(int $performerId, array $highlights): void;
    public function updateTracksByPerformer(int $performerId, array $tracks): void;

    public function getRecommendations(): array;
    public function getRecommendationById(int $id): ?object;
    public function storeRecommendation(array $data): void;
    public function updateRecommendation(array $data): void;
    public function deleteRecommendation(int $id): void;

    public function getLocations(): array;
    public function getLocationById(int $id): ?object;
    public function getLocationsByPerformer(int $performerId): array;
    public function storeLocation(array $data): void;
    public function updateLocation(array $data): void;
    public function deleteLocation(int $id): void;
    public function getActivePerformersForTickets(int $page, int $perf_per_page): array|null|bool;
    public function getNumberOfActivePerformers(): int|bool;
}
