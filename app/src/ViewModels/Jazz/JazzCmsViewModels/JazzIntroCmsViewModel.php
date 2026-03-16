<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzIntroCmsViewModel
{
    public array $intro;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $intro, ?array $currentUser)
    {
        $this->intro = $intro;
        $this->currentUser = $currentUser;
        $this->activeTab = 'intro';
    }
}