<?php
namespace App\ViewModels;

use App\Models\StoryEvent;

class StoriesHomeViewModel
{
    public string $pageTitle;
    public string $pageSubtitle;
    public string $pageDescription;
    public string $bodyHtml;
    public string $heroImage;
    public string $quoteText;
    public string $ctaText;
    public string $pageCSS;
    public string $ticketInfoTitle1;
    public string $ticketInfoBody1;
    public string $ticketInfoNote1;
    public string $ticketInfoTitle2;
    public string $ticketInfoBody2;
    public string $ctaDescription;

    /** @var array<string, StoryEvent[]> */
    public array $eventsByDay;

    public function __construct(
        string $pageTitle,
        string $pageSubtitle,
        string $pageDescription,
        string $bodyHtml,
        string $heroImage,
        string $quoteText,
        string $ctaText,
        string $pageCSS,
        string $ticketInfoTitle1,
        string $ticketInfoBody1,
        string $ticketInfoNote1,
        string $ticketInfoTitle2,
        string $ticketInfoBody2,
        string $ctaDescription,
        array $events
    ) {
        $this->pageTitle        = $pageTitle;
        $this->pageSubtitle     = $pageSubtitle;
        $this->pageDescription  = $pageDescription;
        $this->bodyHtml         = $bodyHtml;
        $this->heroImage        = $heroImage;
        $this->quoteText        = $quoteText;
        $this->ctaText          = $ctaText;
        $this->pageCSS          = $pageCSS;
        $this->ticketInfoTitle1 = $ticketInfoTitle1;
        $this->ticketInfoBody1  = $ticketInfoBody1;
        $this->ticketInfoNote1  = $ticketInfoNote1;
        $this->ticketInfoTitle2 = $ticketInfoTitle2;
        $this->ticketInfoBody2  = $ticketInfoBody2;
        $this->ctaDescription   = $ctaDescription;

        $grouped = [
            'Thursday' => [],
            'Friday'   => [],
            'Saturday' => [],
            'Sunday'   => [],
        ];

        foreach ($events as $event) {
            $day = date('l', strtotime($event->start_time));
            if (isset($grouped[$day])) {
                $grouped[$day][] = $event;
            }
        }

        $this->eventsByDay = $grouped;
    }
}
