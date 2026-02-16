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

    public function getContent()
    {
        $contentArray = $this->repository->getAllContent();
        $content = [];
        
        foreach ($contentArray as $item) {
            $content[$item['section']] = $item;
        }
        
        return $content;
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
            'detail' => $detail,
            'sections' => $this->repository->getDetailSections($detail['id']),
            'gallery' => $this->repository->getDetailGallery($detail['id']),
            'facts' => $this->repository->getDetailFacts($detail['id'])
        ];
    }

    /**
     * Get other highlights to show in "Complete Your Journey" section
     */
    public function getOtherHighlights($currentSlug, $limit = 2)
    {
        $allHighlights = $this->repository->getAllHighlightsWithSlugs();
        
        // Filter out current highlight and limit results
        $others = array_filter($allHighlights, function($h) use ($currentSlug) {
            return $h['slug'] !== $currentSlug && !empty($h['slug']);
        });
        
        return array_slice($others, 0, $limit);
    }
}