<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzPerformersCmsViewModel
{
    public array $performers;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $performers, ?array $currentUser)
    {
        $this->performers = $performers;
        $this->currentUser = $currentUser;
        $this->activeTab = 'performers';
    }
}