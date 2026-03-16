<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzRecommendationsCmsViewModel
{
    public array $recommendations;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $recommendations, ?array $currentUser)
    {
        $this->recommendations = $recommendations;
        $this->currentUser = $currentUser;
        $this->activeTab = 'recommendations';
    }
}