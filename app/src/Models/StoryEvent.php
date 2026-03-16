<?php
namespace App\Models;

class StoryEvent
{
    public int    $event_id;
    public string $name;
    public string $slug;
    public string $description;
    public string $language;
    public string $age_group;
    public string $story_type;
    public bool   $is_pay_as_you_like;
    public string $start_time;
    public string $end_time;
    public int    $max_tickets;
    public float  $price;

    // From Venue JOIN
    public string  $venue_name;
    public string  $venue_address;

    // Optional fields
    public ?string $performer_name;
    public ?string $performer_bio;
    public ?string $image_path;
}
