<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzHero;
use App\Models\User;

class JazzHeroCmsViewModel
{
    public ?JazzHero $hero;
    public ?User $currentUser;
    public string $activeTab;

    public function __construct(?JazzHero $hero, ?User $currentUser)
    {
        $this->hero = $hero;
        $this->currentUser = $currentUser;
        $this->activeTab = 'hero';
    }
}