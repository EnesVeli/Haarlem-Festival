<?php

namespace App\Interfaces\Services;

interface IJazzService
{
    public function getHomePageData(): array;

    public function getPerformerDetail(int $id): ?array;
}