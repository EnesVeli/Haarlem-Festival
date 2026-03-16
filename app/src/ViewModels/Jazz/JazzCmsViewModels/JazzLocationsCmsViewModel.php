<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzLocationsCmsViewModel
{
    public array $locations;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $locations, ?array $currentUser)
    {
        $this->locations = $locations;
        $this->currentUser = $currentUser;
        $this->activeTab = 'locations';
    }
}