<?php
namespace App\Models;

class StoryEvent
{
    public int    $event_id;
    public string $name;
    public string $slug;
    public string $address_name; 
    public string $address_text; 
    public ?string $description;
    public string $language;
    public string $age_group;
    public ?string $story_type;
    public bool $is_pay_as_you_like;
    public string $start_time;
    public string $end_time;
    public int    $max_tickets;
    public ?int $price;
    public string $image_path;

    // Optional fields
    public ?string $performer_name;
    public ?string $performer_bio;

    // Gallery images 
    public ?string $gallery_image_1;
    public ?string $gallery_image_2;

    public function getTypeLabel(): string
    {
        return match($this->story_type) {
            'stories for the whole family'    => 'Stories for the whole family',
            'recording podcast with audience' => 'Recording podcast with audience',
            'stories with impact'             => 'Stories with impact',
            'best of'                         => 'Best of',
            default                           => ucfirst((string) $this->story_type),
        };
    }
}