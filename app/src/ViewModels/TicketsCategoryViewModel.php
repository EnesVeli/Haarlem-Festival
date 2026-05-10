<?php
namespace App\ViewModels;

class TicketsCategoryViewModel extends TicketsCategoryBaseViewModel
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

    public ?array $events = null;

    public function __construct(string $categoryKey, ?array $events, ?int $current_page, ?int $total_page_number)
    {
        $config = self::CONFIG[$categoryKey] ?? null;

        $this->events = $events;

        parent::__construct($categoryKey, (string)($config['title'] ?? 'Festival Tickets'), (string)($config['event_link'] ?? '/tickets'), (string)($config['empty'] ?? $this->emptyMessage), $current_page, $total_page_number);
    }
}
