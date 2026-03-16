<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzDashboardCmsViewModel
{
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(?array $currentUser)
    {
        $this->currentUser = $currentUser;
        $this->activeTab = 'dashboard';
    }
}