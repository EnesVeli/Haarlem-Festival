<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;
use App\Models\Jazz\JazzLocation;
use App\Models\User;

class JazzLocationsCmsViewModel
{
    public array $locations;
    public ?JazzLocation $location;
    public ?User $currentUser;
    public string $activeTab;

    public function __construct(array $locations = [], ?User $currentUser = null, ?JazzLocation $location = null)
    {
        $this->locations = $locations;
        $this->location = $location;
        $this->currentUser = $currentUser;
        $this->activeTab = 'locations';
    }
}