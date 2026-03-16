<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzExperiencesCmsViewModel
{
    public array $experiences;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $experiences, ?array $currentUser)
    {
        $this->experiences = $experiences;
        $this->currentUser = $currentUser;
        $this->activeTab = 'experiences';
    }
}