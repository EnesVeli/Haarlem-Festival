<?php

namespace App\ViewModels\TicketScanner;

class TicketScanViewModel
{
    public string $pageTitle;
    public string $pageCSS;
    public ?array $result;

    public function __construct(?array $result = null)
    {
        $this->pageTitle = 'Ticket Scanner';
        $this->pageCSS = 'jazz.css'; 
        $this->result = $result;
    }
}