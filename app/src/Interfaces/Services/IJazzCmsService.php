<?php

namespace App\Interfaces\Services;

interface IJazzCmsService
{
    public function getDashboardData(): array;
    public function getHeroPageData(): array;
    public function updateHero(array $data, ?array $heroImage = null): void;

    public function getIntroPageData(): array;
    public function updateIntro(array $data): void;
}