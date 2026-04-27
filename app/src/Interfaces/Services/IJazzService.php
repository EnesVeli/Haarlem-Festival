<?php

namespace App\Interfaces\Services;

use App\Models\Jazz\JazzPerformer;

interface IJazzService
{
    public function getHomePageData(): array;

    public function getPerformerDetail(int $id): ?array;

    public function getPerformerById(int $id) : ?JazzPerformer;
}