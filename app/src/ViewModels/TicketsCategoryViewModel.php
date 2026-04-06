<?php
namespace App\ViewModels;

class TicketsCategoryViewModel
{
    private const CONFIG = [
        'jazz' => [
            'title' => 'Haarlem Jazz Tickets',
            'event_link' => '/jazz',
            'empty' => 'No jazz events found at this time.',
        ],
        'dance' => [
            'title' => 'Dance Tickets',
            'event_link' => '/tickets/dance',
            'empty' => 'No dance events found at this time.',
        ],
        'history' => [
            'title' => 'History Tickets',
            'event_link' => '/history',
            'empty' => 'No history events found at this time.',
        ],
        'yummy' => [
            'title' => 'Yummy Tickets',
            'event_link' => '/yummy',
            'empty' => 'No yummy events found at this time.',
        ],
    ];

    /** @var array<string, array<int, array<string, mixed>>> */
    public array $eventsByDay = [];

    public string $csrfToken = '';
    public string $categoryKey = '';
    public string $contentTitle = '';
    public string $eventLink = '/tickets';
    public string $emptyMessage = 'No events found at this time.';
    public string $pageTitle = 'Festival Program – The Festival Haarlem';
    public string $pageCSS = 'tickets.css';

    /**
     * @param array<string, array<int, array<string, mixed>>> $eventsByDay
     */
    public function __construct(string $categoryKey, array $eventsByDay, string $csrfToken)
    {
        $config = self::CONFIG[$categoryKey] ?? null;

        $this->categoryKey = $categoryKey;
        $this->eventsByDay = $eventsByDay;
        $this->csrfToken = $csrfToken;
        $this->contentTitle = (string)($config['title'] ?? 'Festival Tickets');
        $this->eventLink = (string)($config['event_link'] ?? '/tickets');
        $this->emptyMessage = (string)($config['empty'] ?? $this->emptyMessage);
        $this->pageTitle = $this->contentTitle . ' – The Festival Haarlem';
    }
}
