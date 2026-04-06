<?php

namespace App\ViewModels\TicketScanner;

class TicketQrViewModel
{
    public function __construct(
        public string $qr
    ) {
    }
}