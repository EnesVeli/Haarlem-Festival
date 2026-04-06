<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzIntro;

class JazzIntroCmsViewModel
{
    public ?JazzIntro $intro;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(?JazzIntro $intro, ?array $currentUser)
    {
        $this->intro = $intro;
        $this->currentUser = $currentUser;
        $this->activeTab = 'intro';
    }
}