<?php

namespace App\Services;

use App\Repositories\JazzRepository;

class JazzService
{
    private JazzRepository $repo;

    public function __construct()
    {
        $this->repo = new JazzRepository();
    }

    public function getExperiences(): array
    {
        return $this->repo->getActiveExperiences();
    }
}