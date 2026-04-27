<?php

namespace App\Models;

use DateTime;

class Ticket
{
    public int $ticket_id ;
    public int $item_id ;
    public string $qr_token;
    public string $code;
    public ?DateTime $scanned_at;

    public ?OrderItem $order_item;

    function __set($name, $value) {
        if($name == "scanned_at_"){
            $this->scanned_at = $value == null ? null : new DateTime($value);
        }
    }
}