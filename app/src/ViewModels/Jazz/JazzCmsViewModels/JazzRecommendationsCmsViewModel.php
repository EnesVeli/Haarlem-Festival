<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzRecommendationsCmsViewModel
{
    public array $recommendations;
    public array $recommendation;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $recommendations = [], ?array $currentUser = null, array $recommendation = [])
    {
        $this->recommendations = $recommendations;
        $this->recommendation = $recommendation;
        $this->currentUser = $currentUser;
        $this->activeTab = 'recommendations';
    }
}