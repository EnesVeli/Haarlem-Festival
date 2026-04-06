<?php

namespace App\Models;

class FestivalTicket
{
    public function __construct(
        public int $festivalEventTicketId,
        public string $qrToken,
        public string $ticketCode,
        public int $isScanned,
        public ?string $scannedAt,
        public string $title,
        public string $category,
        public string $eventDate,
        public string $startTime,
        public string $location
    ) {
    }
}