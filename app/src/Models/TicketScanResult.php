<?php

namespace App\Models;

class TicketScanResult
{
    public string $status;
    public string $message;
    public ?Ticket $ticket;

    public function __construct(string $status, string $message, ?Ticket $ticket = null)
    {
        $this->status  = $status;
        $this->message = $message;
        $this->ticket  = $ticket;
    }
}
