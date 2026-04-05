<?php
namespace App\ViewModels;

use App\Models\StoryEvent;

/**
 * ViewModel for the Stories tab on the /tickets page.
 *
 * Groups Story events by their day-of-week for chronological display
 * and carries all data the view needs — nothing more, nothing less.
 */
class TicketsStoriesViewModel
{
    /**
     * Events grouped by day name.
     *
     * Structure: ['Thursday, July 23' => StoryEvent[], 'Friday, July 24' => StoryEvent[], ...]
     *
     * @var array<string, StoryEvent[]>
     */
    public array $eventsByDay = [];

    /** @var string CSRF token for the add-to-cart forms. */
    public string $csrfToken = '';

    /** @var string The page title shown in the browser tab. */
    public string $pageTitle = 'Stories Tickets';

    /** @var string The page-specific CSS file to load. */
    public string $pageCSS = 'tickets.css';

    /**
     * Build the ViewModel from a flat list of StoryEvent objects.
     *
     * Events are grouped into day buckets (e.g. "Thursday, July 23") so the
     * view can simply loop over $eventsByDay without any date logic.
     *
     * @param StoryEvent[] $events   All story events, already sorted by start_time ASC
     * @param string       $csrfToken CSRF token for the add-to-cart forms
     */
    public function __construct(array $events = [], string $csrfToken = '')
    {
        $this->csrfToken = $csrfToken;

        foreach ($events as $event) {
            $dayLabel = date('l, F j', strtotime($event->start_time));
            $this->eventsByDay[$dayLabel][] = $event;
        }
    }
}
