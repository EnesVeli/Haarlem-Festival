<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzExperience;

class JazzExperiencesCmsViewModel
{
    public array $experiences;
    public ?JazzExperience $experience;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $experiences, ?array $currentUser, ?JazzExperience $experience = null)
    {
        $this->experiences = $experiences;
        $this->currentUser = $currentUser;
        $this->experience = $experience;
        $this->activeTab = 'experiences';
    }
}