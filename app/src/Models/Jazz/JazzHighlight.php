<?php

namespace App\Models\Jazz;

class JazzHighlight
{
    public function __construct(
        public int $id,
        public int $performerId,
        public string $title,
        public string $description,
        public int $sortOrder
    ) {
    }
}