<?php

namespace App\Models\Jazz;

class JazzExperience
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public ?string $imagePath,
        public int $sortOrder,
        public int $isActive
    ) {
    }
}