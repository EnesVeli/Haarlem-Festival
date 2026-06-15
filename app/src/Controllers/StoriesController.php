<?php
namespace App\Controllers;

use App\Framework\Session;
use App\Interfaces\IStoriesHomepageService;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\PostMismatchException;
use App\Models\StoryBooking;
use App\Services\StoriesHomepageService;
use App\Services\StoriesService;
use App\ViewModels\StoriesHomeViewModel;
use Exception;

/**
 * StoriesController — public-facing Stories pages.
 *
 * Receives both StoriesService (events) and IStoriesHomepageService (CMS content)
 * via constructor injection.
 */
class StoriesController extends BaseController
{
    private StoriesService $service;
    private StoriesHomepageService $homepageService;

    public function __construct()
    {
        $this->service         = StoriesService::getInstance();
        $this->homepageService = StoriesHomepageService::getInstance();
    }

    /**
     * GET /stories — public homepage.
     *
     * @return void
     */
    public function index(): void
    {
        try {
            $cms = $this->homepageService->getStoriesContent();

            $pageDescription = strip_tags($cms->body_html ?? 'During the last weekend of July, the streets of Haarlem transform into a living library...');

            $viewModel = new StoriesHomeViewModel(
                pageTitle:        $cms->title ?? 'Stories in Haarlem',
                pageSubtitle:     $cms->subtitle ?? 'Last Weekend of July | Multiple Locations across Haarlem',
                pageDescription:  $pageDescription,
                bodyHtml:         $cms->body_html ?? '',
                heroImage:        $cms->image_path ?? '/assets/images/stories/stories-hero.jpg',
                quoteText:        $cms->quote_text ?? 'Every street has a sound. Every building has a memory',
                ctaText:          $cms->cta_text ?? 'Ready to plan your festival weekend?',
                pageCSS:          'stories.css',
                ticketInfoTitle1: $cms->ticket_info_title_1 ?? 'Pay as you like',
                ticketInfoBody1:  $cms->ticket_info_body_1 ?? 'Some activities are priced pay as you like. We aim to keep these events as accessible as possible so that everyone has the opportunity to participate. We encourage visitors to donate based on how they valued the experience.',
                ticketInfoNote1:  $cms->ticket_info_note_1 ?? '',
                ticketInfoTitle2: $cms->ticket_info_title_2 ?? 'HaarlemPas discount',
                ticketInfoBody2:  $cms->ticket_info_body_2 ?? 'People with the HaarlemPas receive a 25% discount on entry fees for all stories events with a fixed ticket price.',
                ctaDescription:   $cms->cta_description ?? 'Combine Stories in Haarlem with other festival events across the city and build your perfect weekend program.',
                events:           $this->service->getAllEvents(),
            );

            $this->render('stories/home', [
                'pageCSS'          => $viewModel->pageCSS,
                'pageTitle'        => $viewModel->pageTitle,
                'pageDescription'  => $viewModel->pageDescription,
                'pageSubtitle'     => $viewModel->pageSubtitle,
                'heroImage'        => $viewModel->heroImage,
                'quoteText'        => $viewModel->quoteText,
                'ctaText'          => $viewModel->ctaText,
                'bodyHtml'         => $viewModel->bodyHtml,
                'ticketInfoTitle1' => $viewModel->ticketInfoTitle1,
                'ticketInfoBody1'  => $viewModel->ticketInfoBody1,
                'ticketInfoNote1'  => $viewModel->ticketInfoNote1,
                'ticketInfoTitle2' => $viewModel->ticketInfoTitle2,
                'ticketInfoBody2'  => $viewModel->ticketInfoBody2,
                'ctaDescription'   => $viewModel->ctaDescription,
                'eventsByDay'      => $viewModel->eventsByDay,
            ]);
        } catch (Exception $e) {
            echo 'Something went wrong. Please try again later.';
        }
    }

    /**
     * GET /stories/{slug} — event detail page.
     *
     * @param array $vars Route parameters containing 'slug'
     * @return void
     */
     public function show(array $vars): void
    {
        try {
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
        } catch (Exception $e) {
            echo 'Something went wrong. Please try again later.';
        }
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

        try {
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

            $this->render('stories/book', [
                'pageCSS'   => 'stories.css',
                'event'     => $event,
                'csrfToken' => $this->ensureCsrfToken(),
                'slug'      => $slug,
            ]);
        } catch (Exception $e) {
            echo 'Something went wrong. Please try again later.';
        }
    }

    /**
     * POST /stories/book/add — persist a booking to the cart.
     *
     * @return void
     */
    public function bookAdd() : void {
        if(!$this->isLoggedIn()){
            Session::setTempError("Login first, in order to book a ticket.");
            $this->redirect('/login');
            return;
        }

        if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
            Session::setTempError("Invalid request. Please try again.");
            $this->redirect('/stories');
            return;
        }

        try{
            if(!isset($_POST['event_id']) || !isset($_POST['quantity']) || !isset($_POST['pay_as_you_like'])) throw new EmptyPostException();

            $booking = new StoryBooking();
            $booking->event_id = (int)$_POST['event_id'];
            $booking->quantity = (int)$_POST['quantity'];

            if($_POST['pay_as_you_like'] == 0){
                if(!isset($_POST['haarlem_pas'])) throw new EmptyPostException();

                if($_POST['haarlem_pas'] == 1 && !isset($_POST['haarlempas_code'])) throw new EmptyPostException();

                if($_POST['haarlem_pas'] == 1 && strlen($_POST['haarlempas_code']) != 10) throw new PostMismatchException("haarlempas_code length is inappropriate.");

                $booking->haarlem_pass      = $_POST['haarlem_pas'] == 1;
                $booking->haarlem_pass_code = $_POST['haarlempas_code'] ?? null;
                $booking->pay_as_you_like   = null;
            }
            else{
                if(!isset($_POST['pay_as_you_like_amount'])) throw new EmptyPostException();

                $pay_amount = (int)($_POST['pay_as_you_like_amount'] * 100);

                if($pay_amount > 100000 || $pay_amount < 0) throw new PostMismatchException("pay_as_you_like_amount is inappropriate.");

                $booking->haarlem_pass      = false;
                $booking->haarlem_pass_code = null;
                $booking->pay_as_you_like   = $pay_amount;
            }

           $this->service->createBooking(Session::user()['user_id'], $booking);

        $this->redirect('/cart');
        return;
    }
    catch(Exception $ex){
        Session::setTempError("Something went wrong. Try again later.");

        $slug = preg_replace('/[^a-z0-9\-]/i', '', $_POST['slug'] ?? '');
        if (!empty($slug)) {
            $this->redirect('/stories/' . $slug . '/book');
        } else {
            $this->redirect('/stories');
        }
        return;
    }
    }
}