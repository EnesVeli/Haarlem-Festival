<?php

namespace App\Interfaces\Repositories;

interface IJazzRepository
{
    public function getHero(): ?object;
    public function getIntro(): ?object;
    public function getExperiences(): array;
    public function getRecommendations(): array;
    public function getAllPerformers(bool $onlyActive = true): array;
    public function getPerformerById(int $id): ?object;
    public function getAppearancesByPerformer(int $performerId): array;
    public function getHighlightsByPerformer(int $performerId): array;
    public function getTracksByPerformer(int $performerId): array;
    public function getLocations(): array;
    public function getLocationsByPerformer(int $performerId): array;
}