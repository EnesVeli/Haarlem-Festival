<?php

namespace App\Models\Jazz;

class JazzLocation
{
    public function __construct(
        public int $id,
        public string $name,
        public string $address,
        public ?string $googleMapsEmbedUrl,
        public int $isActive
    ) {
    }
}