<?php

namespace App\ViewModels\Jazz;

class JazzPerformerViewModel
{
    public array $performer;
    public array $appearances;
    public array $highlights;
    public array $tracks;
    public array $locations;
    public array $recommendations;
    public ?array $currentUser;

    public function __construct(
        array $performer,
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