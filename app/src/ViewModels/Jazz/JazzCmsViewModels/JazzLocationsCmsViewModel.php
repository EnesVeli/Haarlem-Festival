<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;
use App\Models\Jazz\JazzLocation;

class JazzLocationsCmsViewModel
{
    public array $locations;
    public ?JazzLocation $location;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $locations = [], ?array $currentUser = null, ?JazzLocation $location = null)
    {
        $this->locations = $locations;
        $this->location = $location;
        $this->currentUser = $currentUser;
        $this->activeTab = 'locations';
    }
}