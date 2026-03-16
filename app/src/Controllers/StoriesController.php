<?php
namespace App\Controllers;

use App\Services\StoriesService;

class StoriesController extends BaseController
{
    private StoriesService $service;

    public function __construct(StoriesService $service)
    {
        $this->service = $service;
    }
    public function index(): void
    {
        $cms = $this->service->getHomepageContent();

        $this->render('Stories/home', [
            'pageCSS'         => 'stories.css',
            'pageTitle'       => $cms['title'] ?? 'Stories in Haarlem',
            'pageDescription' => strip_tags($cms['body_html'] ?? 'During the last weekend of July, the streets of Haarlem transform into a living library...'),
            'pageSubtitle'    => 'Last Weekend of July | Multiple Locations across Haarlem',
            'heroImage'       => $cms['image_path'] ?? '/assets/images/stories/stories-hero.jpg',
            'events'          => $this->service->getAllEvents(),
        ]);
    }

    public function show(array $vars): void
    {
        $slug = htmlspecialchars(trim($vars['slug']));
        $event = $this->service->getEventBySlug($slug);

        if ($event === null) {
            $this->notFound();
            return;
        }

        // Dynamically choose the view template based on the database flag
        $viewTemplate = $event->is_pay_as_you_like ? 'Stories/detail_pay_as_you_like' : 'Stories/detail_fixed';

        $this->render($viewTemplate, [
            'pageCSS' => 'stories.css',
            'event'   => $event,
        ]);
    }

    public function book(array $vars): void
    {
        $slug  = htmlspecialchars(trim($vars['slug']));
        $event = $this->service->getEventBySlug($slug);

        if ($event === null) {
            $this->notFound();
            return;
        }

        $ticketTypes = $this->service->getTicketTypesForEvent($event->event_id);
        $viewTemplate = $event->is_pay_as_you_like ? 'Stories/book_pay_as_you_like' : 'Stories/book_fixed';

        $this->render($viewTemplate, [
            'pageCSS'     => 'stories.css',
            'event'       => $event,
            'ticketTypes' => $ticketTypes,
        ]);
    }
}