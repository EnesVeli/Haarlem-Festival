<?php

namespace App\Models\Jazz;

class JazzRecommendation
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public ?string $url,
        public ?string $imagePath,
        public int $sortOrder,
        public int $isActive
    ) {
    }
}