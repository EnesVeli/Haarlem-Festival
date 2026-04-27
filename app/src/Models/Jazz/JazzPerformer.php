<?php

namespace App\Models\Jazz;

use DateTime;

class JazzPerformer
{
    public int $id;
    public string $name;
    public int $price;
    public ?string $bio;
    public DateTime $date;
    public DateTime $start_time;
    public DateTime $end_time;
    public ?string $performance_style;
    public ?string $venue_name;
    public ?string $venue_address;
    public ?string $note_text;
    public ?string $audio_url;
    public ?string $image_path;
    public ?string $hero_image_path;
    public int $sort_order;
    public int $is_active;

    function __set($name, $value) {
        if($name == "date_") {
            $this->date = new DateTime($value);
        }
        else if($name == "start_time_") {
            $this->start_time = new DateTime($value);
        }
        else if($name == "end_time_") {
            $this->end_time = new DateTime($value);
        }
    }
}