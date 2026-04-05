<?php

namespace App\Models\Jazz;

class JazzIntro
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description
    ) {
    }
}