<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\User;

class JazzDashboardCmsViewModel
{
    public ?User $currentUser;
    public string $activeTab;

    public function __construct(?User $currentUser)
    {
        $this->currentUser = $currentUser;
        $this->activeTab = 'dashboard';
    }
}