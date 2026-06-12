<?php
namespace App\ViewModels;

class HomeEditViewModel
{
    public array  $content;
    public array  $eventCards;
    public string $pageTitle;

    public function __construct(array $content, array $eventCards)
    {
        $this->content    = $content;
        $this->eventCards = $eventCards;
        $this->pageTitle  = 'CMS – Edit Homepage';
    }

    /**
     * Helper: return a content value with a fallback.
     */
    public function get(string $key, string $default = ''): string
    {
        return htmlspecialchars($this->content[$key] ?? $default);
    }
}