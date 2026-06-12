<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzExperience;
use App\Models\User;

class JazzExperiencesCmsViewModel
{
    public array $experiences;
    public ?JazzExperience $experience;
    public ?User $currentUser;
    public string $activeTab;

    public function __construct(array $experiences, ?User $currentUser, ?JazzExperience $experience = null)
    {
        $this->experiences = $experiences;
        $this->currentUser = $currentUser;
        $this->experience = $experience;
        $this->activeTab = 'experiences';
    }
}