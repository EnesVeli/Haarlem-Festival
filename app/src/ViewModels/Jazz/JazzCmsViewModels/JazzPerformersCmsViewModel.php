<?php

namespace App\ViewModels\Jazz\JazzCmsViewModels;

use App\Models\Jazz\JazzPerformer;
use App\Models\User;

class JazzPerformersCmsViewModel
{
    public array $performers;
    public ?JazzPerformer $performer;
    public ?User $currentUser;
    public string $activeTab;
    public array $highlights;
    public array $tracks;

    public function __construct(
        array $performers,
        ?User $currentUser,
        ?JazzPerformer $performer = null,
        array $highlights = [],
        array $tracks = []
    ) {
        $this->performers = $performers;
        $this->currentUser = $currentUser;
        $this->performer = $performer;
        $this->highlights = $highlights;
        $this->tracks = $tracks;
        $this->activeTab = 'performers';
    }
}