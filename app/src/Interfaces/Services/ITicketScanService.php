<?php

namespace App\Interfaces\Services;

use App\Models\Ticket;

interface ITicketScanService
{
    public function scanTicket(string $scan_value): Ticket;

    public function getTicketQr(int $ticketId): ?string;
}
