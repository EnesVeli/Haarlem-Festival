<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzHeroCmsViewModel
{
    public array $hero;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $hero, ?array $currentUser)
    {
        $this->hero = $hero;
        $this->currentUser = $currentUser;
        $this->activeTab = 'hero';
    }
}