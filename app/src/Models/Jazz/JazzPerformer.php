<?php

namespace App\Models\Jazz;

class JazzPerformer
{
    public function __construct(
        public int $id,
        public string $name,
        public int $price,
        public string $bio,
        public ?string $performanceStyle,
        public ?string $eventDateText,
        public ?string $eventTimeText,
        public ?string $venueName,
        public ?string $venueAddress,
        public ?string $noteText,
        public ?string $audioUrl,
        public ?string $imagePath,
        public ?string $heroImagePath,
        public int $sortOrder,
        public int $isActive
    ) {
    }
}