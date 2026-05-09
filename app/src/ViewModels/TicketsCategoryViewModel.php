<?php
namespace App\ViewModels;

class TicketsCategoryViewModel
{
    private const CONFIG = [
        'jazz' => [
            'title' => 'Jazz Tickets',
            'event_link' => '/jazz',
            'empty' => 'No jazz events found at this time.',
        ],
        'stories' => [
            'title' => 'Stories Tickets',
            'event_link' => '/stories',
            'empty' => 'No stories events found at this time.',
        ],
        'history' => [
            'title' => 'History Tickets',
            'event_link' => '/history',
            'empty' => 'No history events found at this time.',
        ],
        'yummy' => [
            'title' => 'Yummy Tickets',
            'event_link' => '/yummy',
            'empty' => 'No yummy restaurants found at this time.',
        ],
    ];

    public string $categoryKey = '';
    public ?array $events = null;
    public string $contentTitle = '';
    public string $eventLink = '/tickets';
    public string $emptyMessage = 'No events found at this time.';
    public string $pageTitle = 'Festival Program - The Festival Haarlem';
    public string $pageCSS = 'tickets.css';

    public function __construct(string $categoryKey, ?array $events)
    {
        $config = self::CONFIG[$categoryKey] ?? null;

        $this->categoryKey = $categoryKey;
        $this->events = $events;
        $this->contentTitle = (string)($config['title'] ?? 'Festival Tickets');
        $this->eventLink = (string)($config['event_link'] ?? '/tickets');
        $this->emptyMessage = (string)($config['empty'] ?? $this->emptyMessage);
        $this->pageTitle = $this->contentTitle . ' - The Festival Haarlem';
    }
}
