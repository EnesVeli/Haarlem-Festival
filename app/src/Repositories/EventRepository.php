<?php

namespace App\Repositories;

use App\Framework\Repository;

class EventRepository extends Repository
{
    /**
     * Returns the festival events shown on the homepage / navigation.
     */
    public function getHomepageEvents(): array
    {
        return [
            ['slug' => 'jazz',    'title' => 'Haarlem Jazz',            'category' => 'Music',   'description' => 'World-class jazz performances across the city.'],
            ['slug' => 'dance',   'title' => 'DANCE!',                 'category' => 'Music',   'description' => 'Three nights of house, techno and trance.'],
            ['slug' => 'yummy',   'title' => 'Yummy!',                 'category' => 'Food',    'description' => 'Exclusive festival menus from top restaurants.'],
            ['slug' => 'history', 'title' => 'A Stroll Through History','category' => 'Culture', 'description' => 'Guided walks through Haarlem’s past.'],
            ['slug' => 'stories', 'title' => 'Stories in Haarlem',     'category' => 'Culture', 'description' => 'Live storytelling across the city.'],
        ];
    }
}