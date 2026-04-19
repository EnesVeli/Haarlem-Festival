<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Interfaces\IStoriesHomepageService;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\PostMismatchException;
use App\Models\StoryBooking;
use App\Services\StoriesHomepageService;
use App\Services\StoriesService;
use Exception;

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
            'slug' => $slug
        ]);
    }

    public function bookAdd() : void {
        if(!$this->isLoggedIn()){
            Session::setTempError("Login first, in order to book a ticket.");
            header("loaction: /login");
            exit;
        }

        try{
            if(!isset($_POST['event_id']) || !isset($_POST['quantity']) || !isset($_POST['haarlem_pas'])) throw new EmptyPostException();

            if($_POST['haarlem_pas'] == 1 && !isset($_POST['haarlempas_code'])) throw new EmptyPostException();

            if($_POST['haarlem_pas'] == 1 && strlen($_POST['haarlempas_code']) != 10) throw new PostMismatchException();

            $booking = new StoryBooking();
            $booking->event_id = $_POST['event_id'];
            $booking->quantity = $_POST['quantity'];
            $booking->haarlem_pass = $_POST['haarlem_pas'] == 1;
            $booking->haarlem_pass_code = $_POST['haarlempas_code'] ?? null;

            $this->service->createBooking(Session::user()['user_id'], $booking);

            header("location: /cart");
            exit;
        }
        catch(Exception $ex){
            Session::setTempError("Somethong went wrong. Try again later.");
        }

        if(isset($_POST['slug'])){
            header("loaction: /stories/" . $_POST['slug'] . '/book');
        }
        else{
            header("loaction: /stories");
        }
    }
}
