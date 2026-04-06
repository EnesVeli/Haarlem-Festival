<?php

namespace App\Models\Jazz;

class JazzAppearance
{
    public function __construct(
        public int $id,
        public int $performerId,
        public string $dayText,
        public string $timeText,
        public ?string $locationText,
        public ?string $noteText,
        public int $sortOrder
    ) {
    }
}