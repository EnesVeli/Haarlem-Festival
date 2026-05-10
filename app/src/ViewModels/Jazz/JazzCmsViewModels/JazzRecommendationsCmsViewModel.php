<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzRecommendation;
use App\Models\User;

class JazzRecommendationsCmsViewModel
{
    public array $recommendations;
    public ?JazzRecommendation $recommendation;
    public ?User $currentUser;
    public string $activeTab;

    public function __construct(array $recommendations, ?User $currentUser, ?JazzRecommendation $recommendation = null)
    {
        $this->recommendations = $recommendations;
        $this->currentUser = $currentUser;
        $this->recommendation = $recommendation;
        $this->activeTab = 'recommendations';
    }
}