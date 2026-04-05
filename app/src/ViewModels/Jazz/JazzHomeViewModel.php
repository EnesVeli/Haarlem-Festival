<?php

namespace App\ViewModels\Jazz;

use App\Models\Jazz\JazzHero;
use App\Models\Jazz\JazzIntro;

class JazzHomeViewModel
{
    public ?JazzHero $hero;
    public ?JazzIntro $intro;
    public array $experiences;
    public array $performers;
    public array $recommendations;
    public array $locations;
    public ?array $currentUser;

    public function __construct(
        ?JazzHero $hero,
        ?JazzIntro $intro,
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