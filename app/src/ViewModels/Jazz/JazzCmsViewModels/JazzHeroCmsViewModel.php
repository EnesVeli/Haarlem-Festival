<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzHero;

class JazzHeroCmsViewModel
{
    public ?JazzHero $hero;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(?JazzHero $hero, ?array $currentUser)
    {
        $this->hero = $hero;
        $this->currentUser = $currentUser;
        $this->activeTab = 'hero';
    }
}