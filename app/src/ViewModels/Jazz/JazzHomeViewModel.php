<?php

namespace App\ViewModels\Jazz;

class JazzHomeViewModel
{
    public ?array $hero;
    public ?array $intro;
    public array $experiences;
    public array $performers;
    public array $recommendations;
    public array $locations;
    public ?array $currentUser;

    public function __construct(
        ?array $hero,
        ?array $intro,
        array $experiences,
        array $performers,
        array $recommendations,
        array $locations,
        ?array $currentUser
    ) {
        $this->hero = $hero;
        $this->intro = $intro;
        $this->experiences = $experiences;
        $this->performers = $performers;
        $this->recommendations = $recommendations;
        $this->locations = $locations;
        $this->currentUser = $currentUser;
    }
}