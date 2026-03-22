<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzLocationsCmsViewModel
{
    public array $locations;
    public array $location;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $locations = [], ?array $currentUser = null, array $location = [])
    {
        $this->locations = $locations;
        $this->location = $location;
        $this->currentUser = $currentUser;
        $this->activeTab = 'locations';
    }
}