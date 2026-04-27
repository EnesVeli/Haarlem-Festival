<?php

namespace App\ViewModels\History;

class HistoryIndexViewModel
{
    public array $highlights;
    public array $time_slots;
    public array $content;  // keyed by section name, e.g. $content['hero']
    public int $max_date_offset;

    public function __construct(array $highlights, array $time_slots, array $rawContent, int $max_date_offset)
    {
        $this->highlights = $highlights;
        $this->time_slots    = $time_slots;
        $this->content    = $this->keyContentBySection($rawContent);
        $this->max_date_offset = $max_date_offset;
    }

    // Turns the flat DB rows into a section-keyed array so views can do $content['hero']
    private function keyContentBySection(array $rows): array
    {
        $keyed = [];
        foreach ($rows as $row) {
            $keyed[$row['section']] = $row;
        }
        return $keyed;
    }

    // ── Content getters with sensible fallback defaults ────────────────────

    public function heroImage(): string
    {
        return $this->content['hero']['image'] ?? '';
    }

    public function heroTitle(): string
    {
        return $this->content['hero']['title'] ?? "A Journey Through Haarlem's Legacy";
    }

    public function heroSubtitle(): string
    {
        return $this->content['hero']['subtitle']
            ?? "Discover the city of painters, merchants, and hidden courtyards. Experience 775 years of history in one unforgettable walk.";
    }

    public function introTitle(): string
    {
        return $this->content['intro']['title'] ?? "The Golden City of the North";
    }

    public function introSubtitle(): string
    {
        return $this->content['intro']['subtitle']
            ?? "Long before Amsterdam rose to global fame, Haarlem was the beating heart of Holland.";
    }

    public function walkTitle(): string
    {
        return $this->content['walk']['title'] ?? 'Better Your Walk';
    }

    public function walkSubtitle(): string
    {
        return $this->content['walk']['subtitle']
            ?? 'You can walk the route freely, but for the full story, our expert guides bring the stones to life.';
    }

    public function walkImage(): string
    {
        return $this->content['walk']['image'] ?? '';
    }

    public function hasWalkImage(): bool
    {
        return !empty($this->content['walk']['image']);
    }

    public function ctaTitle(): string
    {
        return $this->content['cta']['title'] ?? 'Ready to plan your festival weekend?';
    }

    public function ctaSubtitle(): string
    {
        return $this->content['cta']['subtitle']
            ?? 'Combine Stories in Haarlem with other Festival events across the city and build your perfect weekend program.';
    }

    public function ctaImage(): string
    {
        return $this->content['cta']['image'] ?? '';
    }

    public function hasCtaImage(): bool
    {
        return !empty($this->content['cta']['image']);
    }
}