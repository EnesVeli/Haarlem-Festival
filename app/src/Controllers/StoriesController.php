<?php
namespace App\Controllers;

use App\Interfaces\IStoriesHomepageService;
use App\Services\StoriesHomepageService;
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

    public function __construct()
    {
        $this->service         = new StoriesService();
        $this->homepageService = new StoriesHomepageService();
    }

    /**
     * GET /stories — public homepage.
     *
     * @return void
     */
    public function index(): void
    {
        $cms = $this->homepageService->getStoriesContent();

        $this->render('stories/home', [
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
        $slug = trim((string) ($vars['slug'] ?? ''));
        if (!preg_match('/^[a-z0-9\-]+$/i', $slug)) {
            $this->notFound();
            return;
        }

        $event = $this->service->getEventBySlug($slug);

        if ($event === null) {
            $this->notFound();
            return;
        }

        // Fetch all sessions sharing this event's name for the schedule sidebar
        $schedule = $this->service->getScheduleForEvent($event->name);

        $this->render('stories/detail', [
            'pageCSS'  => 'stories.css',
            'event'    => $event,
            'schedule' => $schedule,
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
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $slug = trim((string) ($vars['slug'] ?? ''));
        if (!preg_match('/^[a-z0-9\-]+$/i', $slug)) {
            $this->notFound();
            return;
        }

        $event = $this->service->getEventBySlug($slug);

        if ($event === null) {
            $this->notFound();
            return;
        }

        $viewTemplate = $event->is_pay_as_you_like ? 'stories/book_pay_as_you_like' : 'stories/book_fixed';

        $this->render($viewTemplate, [
            'pageCSS'     => 'stories.css',
            'event'       => $event,
            'csrfToken'   => $this->ensureCsrfToken(),
        ]);
    }

    public function bookAdd(){
        
    }
}
