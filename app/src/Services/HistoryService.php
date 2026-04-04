<?php

namespace App\Services;

use App\Repositories\HistoryRepository;

class HistoryService
{
    private HistoryRepository $repository;

    public function __construct()
    {
        $this->repository = new HistoryRepository();
    }

    public function getHighlights()
    {
        return $this->repository->getAllHighlightsWithSlugs();
    }

    /**
     * Returns only individual time-slot tickets.
     * Used by the index page sidebar to display available slots.
     */
    public function getTickets(): array
    {
        $grouped = $this->repository->getAvailableTickets();
        return $grouped['individual'] ?? [];
    }

    /**
     * Returns tickets grouped by type: ['individual' => [...], 'family' => [...]]
     * Used by the booking page so both prices come from the DB.
     */
    public function getGroupedTickets(): array
    {
        return $this->repository->getAvailableTickets();
    }

    public function getContent(): array
    {
        return $this->repository->getAllContent();
    }

    public function getContentBySection($section)
    {
        return $this->repository->getContentBySection($section);
    }

    /**
     * Get complete detail page data
     */
    public function getDetailPage($slug)
    {
        $detail = $this->repository->getDetailBySlug($slug);

        if (!$detail) {
            return null;
        }

        return [
            'detail'   => $detail,
            'sections' => $this->repository->getDetailSections($detail['id']),
            'gallery'  => $this->repository->getDetailGallery($detail['id']),
            'facts'    => $this->repository->getDetailFacts($detail['id']),
        ];
    }

    /**
     * Get other highlights to show in "Complete Your Journey" section
     */
    public function getOtherHighlights($currentSlug, $limit = 2)
    {
        $allHighlights = $this->repository->getAllHighlightsWithSlugs();

        $others = array_filter($allHighlights, function ($h) use ($currentSlug) {
            return $h['slug'] !== $currentSlug && !empty($h['slug']);
        });

        return array_slice($others, 0, $limit);
    }
}