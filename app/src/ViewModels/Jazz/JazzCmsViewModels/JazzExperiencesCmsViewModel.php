<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzExperiencesCmsViewModel
{
    public array $experiences;
    public array $experience;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $experiences = [], ?array $currentUser = null, array $experience = [])
    {
        $this->experiences = $experiences;
        $this->experience = $experience;
        $this->currentUser = $currentUser;
        $this->activeTab = 'experiences';
    }
}