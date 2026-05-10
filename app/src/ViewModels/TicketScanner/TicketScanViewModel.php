<?php

namespace App\ViewModels\TicketScanner;

use App\Models\TicketScanResult;

class TicketScanViewModel
{
    public string $pageTitle;
    public string $pageCSS;
    public ?TicketScanResult $result;

    public function __construct(?TicketScanResult $result = null)
    {
        $this->pageTitle = 'Ticket Scanner';
        $this->pageCSS   = 'jazz.css';
        $this->result    = $result;
    }
}
