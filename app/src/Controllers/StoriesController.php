<?php
namespace App\Controllers;

use App\Services\StoriesService;

class StoriesController extends BaseController
{
    private StoriesService $service;

    public function __construct()
    {
        $this->service = new StoriesService();
    }

    // GET /stories — shows the homepage with the full programme
    public function index(): void
    {
        $events = $this->service->getAllEvents();

        $this->render('Stories/home', [
            'pageCSS'         => 'stories.css',
            'pageTitle'       => 'Stories in Haarlem',
            'pageSubtitle'    => 'Last Weekend of July | Multiple Locations across Haarlem',
            'pageDescription' => 'During the last weekend of July, the streets of Haarlem transform into a living library...',
            'events'          => $events,
        ]);
    }

    // GET /stories/{slug} — shows one event detail page
    public function show(array $vars): void
    {
        $event = $this->service->getEventBySlug($vars['slug']);

        // If the slug doesn't exist in DB, show 404
        if ($event === null) {
            $this->notFound();
        }

        $this->render('Stories/detail', [
            'pageCSS' => 'stories.css',
            'event'   => $event,
        ]);
    }
}
