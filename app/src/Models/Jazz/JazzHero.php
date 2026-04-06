<?php

namespace App\Models\Jazz;

class JazzHero
{
    public function __construct(
        public int $id,
        public string $title,
        public string $subtitle,
        public ?string $imagePath,
        public int $isActive
    ) {
    }
}