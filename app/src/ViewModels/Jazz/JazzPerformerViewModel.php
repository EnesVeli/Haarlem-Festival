<?php

namespace App\ViewModels\Jazz;

use App\Models\Jazz\JazzPerformer;

class JazzPerformerViewModel
{
    public JazzPerformer $performer;
    public array $appearances;
    public array $highlights;
    public array $tracks;
    public array $locations;
    public array $recommendations;
    public ?array $currentUser;

    public function __construct(
        JazzPerformer $performer,
        array $appearances,
        array $highlights,
        array $tracks,
        array $locations,
        array $recommendations,
        ?array $currentUser
    ) {
        $this->performer = $performer;
        $this->appearances = $appearances;
        $this->highlights = $highlights;
        $this->tracks = $tracks;
        $this->locations = $locations;
        $this->recommendations = $recommendations;
        $this->currentUser = $currentUser;
    }
}