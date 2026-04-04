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

    // Used by the index page — returns only individual time-slot tickets (flat array)
    public function getTickets(): array
    {
        return $this->getGroupedTickets()['individual'];
    }

    // Used by the booking page — returns tickets split into ['individual' => [...], 'family' => [...]]
    public function getGroupedTickets(): array
    {
        return $this->repository->getAvailableTickets();
    }

    public function getHighlights(): array
    {
        return $this->repository->getAllHighlightsWithSlugs();
    }

    public function getContent(): array
    {
        return $this->repository->getAllContent();
    }

    public function getContentBySection(string $section): array|false
    {
        return $this->repository->getContentBySection($section);
    }

    // Returns all data needed to render a highlight's detail page
    public function getDetailPage(string $slug): ?array
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

    // Returns up to $limit other highlights for the "Complete Your Journey" section
    public function getOtherHighlights(string $currentSlug, int $limit = 2): array
    {
        $all = $this->repository->getAllHighlightsWithSlugs();

        $others = array_filter($all, fn($h) => $h['slug'] !== $currentSlug && !empty($h['slug']));

        return array_slice(array_values($others), 0, $limit);
    }
}