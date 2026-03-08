<?php

namespace App\ViewModels;

class HistoryDetailViewModel
{
    public string $pageTitle;
    public string $heroImage;
    public string $location;
    public string $foundedYear;
    public string $styleType;
    public array  $sections;
    public array  $gallery;
    public array  $facts;
    public array  $otherHighlights;

    public function __construct(array $detail, array $sections, array $gallery, array $facts, array $otherHighlights)
    {
        $this->pageTitle       = $detail['page_title'] ?? '';
        $this->heroImage       = $detail['hero_image'] ?? '';
        $this->location        = $detail['location'] ?? '';
        $this->foundedYear     = $detail['founded_year'] ?? '';
        $this->styleType       = $detail['style_type'] ?? '';
        $this->sections        = $sections;
        $this->gallery         = $gallery;
        $this->facts           = $facts;
        $this->otherHighlights = $this->filterValidHighlights($otherHighlights);
    }

    public function fullPageTitle(): string
    {
        return htmlspecialchars($this->pageTitle) . ' - Haarlem Festival';
    }

    public function hasGallery(): bool
    {
        return !empty($this->gallery);
    }

    public function hasFacts(): bool
    {
        return !empty($this->facts);
    }

    public function hasLocation(): bool
    {
        return !empty($this->location);
    }

    public function hasFoundedYear(): bool
    {
        return !empty($this->foundedYear);
    }

    public function hasStyleType(): bool
    {
        return !empty($this->styleType);
    }

    
    public function getParagraphs(array $section): array
    {
        $paragraphs = explode("\n\n", $section['content'] ?? '');
        return array_filter($paragraphs, fn($p) => trim($p) !== '');
    }

    private function filterValidHighlights(array $highlights): array
    {
        return array_values(array_filter($highlights, fn($h) => !empty($h['slug'])));
    }
}