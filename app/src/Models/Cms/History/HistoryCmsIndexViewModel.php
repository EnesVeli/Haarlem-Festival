<?php

namespace App\ViewModels\Cms\History;

use App\Models\History\HistoryContent;
use App\Models\History\HistoryDetail;
use App\Models\History\HistoryHighlight;

/**
 * View model for the History CMS dashboard (/cms/history).
 *
 * Bundles everything the index view needs across its tabs:
 * highlights, page content (keyed by section), detail pages,
 * and the ticket prices.
 */
class HistoryCmsIndexViewModel
{
    /** @var HistoryHighlight[] */
    public array $highlights;

    /** @var array<string, HistoryContent> */
    public array $content;

    /** @var HistoryDetail[] */
    public array $details;

    /** Price in whole euros (converted from cents). */
    public float $individualPrice;

    /** Price in whole euros (converted from cents). */
    public float $familyPrice;

    /**
     * @param HistoryHighlight[]            $highlights
     * @param array<string, HistoryContent> $content
     * @param HistoryDetail[]                $details
     */
    public function __construct(
        array $highlights,
        array $content,
        array $details,
        int $individualPriceCents,
        int $familyPriceCents
    ) {
        $this->highlights = $highlights;
        $this->content = $content;
        $this->details = $details;
        $this->individualPrice = $individualPriceCents / 100;
        $this->familyPrice = $familyPriceCents / 100;
    }

    /** Empty view model used when loading the dashboard data fails. */
    public static function empty(): self
    {
        return new self([], [], [], 0, 0);
    }

    /**
     * Get a field from a content section, with a fallback default.
     */
    public function contentValue(string $section, string $field, string $default = ''): string
    {
        $content = $this->content[$section] ?? null;

        if ($content === null) {
            return $default;
        }

        return match ($field) {
            'title'       => $content->title ?? $default,
            'subtitle'    => $content->subtitle ?? $default,
            'image'       => $content->image ?? $default,
            'image_left'  => $content->imageLeft ?? $default,
            'image_right' => $content->imageRight ?? $default,
            default       => $default,
        };
    }
}
