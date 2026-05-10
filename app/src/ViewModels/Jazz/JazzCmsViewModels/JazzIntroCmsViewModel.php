<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzIntro;
use App\Models\User;

class JazzIntroCmsViewModel
{
    public ?JazzIntro $intro;
    public ?User $currentUser;
    public string $activeTab;

    public function __construct(?JazzIntro $intro, ?User $currentUser)
    {
        $this->intro = $intro;
        $this->currentUser = $currentUser;
        $this->activeTab = 'intro';
    }
}