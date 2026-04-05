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

    public function getTickets()
    {
        return $this->repository->getAvailableTickets();
    }

    public function getTicketPrices(): array
    {
        return $this->repository->getTicketPrices();
    }

    public function updateTicketPrice(string $type, float $price): void
    {
        $this->repository->updateTicketPrice($type, $price);
    }

    public function getContent(): array
    {
        return $this->repository->getAllContent();
    }

    public function getContentBySection($section)
    {
        return $this->repository->getContentBySection($section);
    }

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

    public function getOtherHighlights($currentSlug, $limit = 2)
    {
        $allHighlights = $this->repository->getAllHighlightsWithSlugs();

        $others = array_filter($allHighlights, function ($h) use ($currentSlug) {
            return $h['slug'] !== $currentSlug && !empty($h['slug']);
        });

        return array_slice($others, 0, $limit);
    }
}