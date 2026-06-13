<?php

namespace App\ViewModels;

use App\Models\HomeEvent;

/**
 * View model for the Home CMS editor (/cms/home).
 */
class HomeEditViewModel
{
    /** @var array<string, string> */
    public array $content;

    /** @var HomeEvent[] */
    public array $eventCards;

    public string $pageTitle;

    /**
     * @param array<string, string> $content
     * @param HomeEvent[]            $eventCards
     */
    public function __construct(array $content, array $eventCards)
    {
        $this->content = $content;
        $this->eventCards = $eventCards;
        $this->pageTitle = 'CMS – Edit Homepage';
    }

    /**
     * Helper: return a content value with a fallback, HTML-escaped.
     */
    public function get(string $key, string $default = ''): string
    {
        return htmlspecialchars($this->content[$key] ?? $default);
    }
}
