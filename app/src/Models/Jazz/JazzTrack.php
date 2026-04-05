<?php

namespace App\Models\Jazz;

class JazzTrack
{
    public function __construct(
        public int $id,
        public int $performerId,
        public string $title,
        public ?string $releaseDateText,
        public ?string $description,
        public ?string $imagePath,
        public ?string $listenUrl,
        public int $sortOrder
    ) {
    }
}