<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzRecommendation;

class JazzRecommendationsCmsViewModel
{
    public array $recommendations;
    public ?JazzRecommendation $recommendation;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $recommendations, ?array $currentUser, ?JazzRecommendation $recommendation = null)
    {
        $this->recommendations = $recommendations;
        $this->currentUser = $currentUser;
        $this->recommendation = $recommendation;
        $this->activeTab = 'recommendations';
    }
}