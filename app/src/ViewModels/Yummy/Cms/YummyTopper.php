<?php

namespace App\ViewModels\Yummy\Cms;

class YummyTopper {
    public string $title;
    public ?string $subtitle;
    public string $button_text;
    public string $button_link;
    public int $active_tab;
}