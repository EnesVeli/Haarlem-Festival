<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

class JazzPerformersCmsViewModel
{
    public array $performers;
    public array $performer;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $performers = [], ?array $currentUser = null, array $performer = [])
    {
        $this->performers = $performers;
        $this->performer = $performer;
        $this->currentUser = $currentUser;
        $this->activeTab = 'performers';
    }
}