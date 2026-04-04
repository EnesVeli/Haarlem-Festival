<?php
namespace App\Services;

use App\Repositories\NonStoriesTicketsRepository;
use InvalidArgumentException;

class NonStoriesTicketsService
{
    private const CATEGORY_TYPE_MAP = [
        'jazz' => 1,
        'dance' => 2,
        'history' => 3,
        'yummy' => 4,
    ];

    private NonStoriesTicketsRepository $repository;
    private CartService $cartService;

    public function __construct(NonStoriesTicketsRepository $repository, CartService $cartService)
    {
        $this->repository = $repository;
        $this->cartService = $cartService;
    }

    /**
     * Returns grouped events for one category key.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function getCategoryTickets(string $category): array
    {
        $type = self::CATEGORY_TYPE_MAP[$category] ?? null;
        if ($type === null) {
            throw new InvalidArgumentException('Unknown category: ' . $category);
        }

        $rows = $this->repository->getByType($type);
        $grouped = [];

        foreach ($rows as $row) {
            $eventId = (int)($row['event_id'] ?? 0);
            if ($eventId <= 0) {
                continue;
            }

            $dayLabel = date('l, F j', strtotime((string)$row['start_time']));
            $grouped[$dayLabel][] = [
                'event_id' => $eventId,
                'name' => (string)($row['name'] ?? ''),
                'slug' => (string)($row['slug'] ?? ''),
                'description' => (string)($row['description'] ?? ''),
                'start_time' => (string)($row['start_time'] ?? ''),
                'end_time' => (string)($row['end_time'] ?? ''),
                'venue_name' => (string)($row['venue_name'] ?? ''),
                'venue_address' => (string)($row['venue_address'] ?? ''),
                'ticket_type_id' => isset($row['ticket_type_id']) ? (int)$row['ticket_type_id'] : 0,
                'price' => isset($row['price']) ? (float)$row['price'] : 0.0,
                'available' => $this->cartService->getSellableAvailability($eventId),
            ];
        }

        return $grouped;
    }
}
