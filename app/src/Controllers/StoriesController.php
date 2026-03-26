<?php
namespace App\Controllers;

use App\Interfaces\IStoriesHomepageService;
use App\Services\StoriesService;

/**
 * StoriesController — public-facing Stories pages.
 *
 * Receives both StoriesService (events) and IStoriesHomepageService (CMS content)
 * via constructor injection.
 */
class StoriesController extends BaseController
{
    /** @var StoriesService */
    private StoriesService $service;

    /** @var IStoriesHomepageService */
    private IStoriesHomepageService $homepageService;

    /**
     * @param StoriesService          $service         Event service
     * @param IStoriesHomepageService $homepageService CMS homepage content service
     */
    public function __construct(StoriesService $service, IStoriesHomepageService $homepageService)
    {
        $this->service         = $service;
        $this->homepageService = $homepageService;
    }

    /**
     * GET /stories — public homepage.
     *
     * @return void
     */
    public function index(): void
    {
        $cms = $this->homepageService->getStoriesContent();

        $this->render('Stories/home', [
            'pageCSS'          => 'stories.css',
            'pageTitle'        => $cms->title ?? 'Stories in Haarlem',
            'pageDescription'  => strip_tags($cms->body_html ?? 'During the last weekend of July, the streets of Haarlem transform into a living library...'),
            'pageSubtitle'     => $cms->subtitle ?? 'Last Weekend of July | Multiple Locations across Haarlem',
            'heroImage'        => $cms->image_path ?? '/assets/images/stories/stories-hero.jpg',
            'quoteText'        => $cms->quote_text ?? 'Every street has a sound. Every building has a memory',
            'ctaText'          => $cms->cta_text ?? 'Ready to plan your festival weekend?',
            'bodyHtml'         => $cms->body_html ?? '',
            'events'           => $this->service->getAllEvents(),
            'homepageContent'  => $cms,
        ]);
    }

    /**
     * GET /stories/{slug} — event detail page.
     *
     * @param array $vars Route parameters containing 'slug'
     * @return void
     */
    public function show(array $vars): void
    {
        $slug = htmlspecialchars(trim($vars['slug']));
        $event = $this->service->getEventBySlug($slug);

        if ($event === null) {
            $this->notFound();
            return;
        }

        $viewTemplate = $event->is_pay_as_you_like ? 'Stories/detail_pay_as_you_like' : 'Stories/detail_fixed';

        $this->render($viewTemplate, [
            'pageCSS' => 'stories.css',
            'event'   => $event,
        ]);
    }

    /**
     * GET /stories/{slug}/book — booking page for an event.
     *
     * @param array $vars Route parameters containing 'slug'
     * @return void
     */
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