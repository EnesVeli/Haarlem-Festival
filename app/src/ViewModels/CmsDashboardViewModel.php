<?php

namespace App\ViewModels;

class CmsDashboardViewModel
{
    public ?array $currentUser;
    public array $sections;

    public function __construct(?array $currentUser = null, array $sections = [])
    {
        $this->currentUser = $currentUser;
        $this->sections = $sections;
    }
}