<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzPerformer;

class JazzPerformersCmsViewModel
{
    public array $performers;
    public ?JazzPerformer $performer;
    public ?array $currentUser;
    public string $activeTab;

    public function __construct(array $performers, ?array $currentUser, ?JazzPerformer $performer = null)
    {
        $this->performers = $performers;
        $this->currentUser = $currentUser;
        $this->performer = $performer;
        $this->activeTab = 'performers';
    }
}