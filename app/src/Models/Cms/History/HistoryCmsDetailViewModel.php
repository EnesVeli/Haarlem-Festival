<?php

namespace App\ViewModels\Cms\History;

use App\Models\History\HistoryDetail;
use App\Models\History\HistoryDetailSection;
use App\Models\History\HistoryFact;
use App\Models\History\HistoryGalleryImage;
use App\Models\History\HistoryHighlight;

/**
 * View model for the History CMS detail-page editor (/cms/history/detail/{id}).
 */
class HistoryCmsDetailViewModel
{
    public ?HistoryDetail $detail;

    /** @var HistoryHighlight[] */
    public array $highlights;

    /** @var HistoryDetailSection[] */
    public array $sections;

    /** @var HistoryGalleryImage[] */
    public array $gallery;

    /** @var HistoryFact[] */
    public array $facts;

    public bool $isNew;

    /**
     * @param HistoryHighlight[]      $highlights
     * @param HistoryDetailSection[]  $sections
     * @param HistoryGalleryImage[]   $gallery
     * @param HistoryFact[]           $facts
     */
    public function __construct(
        ?HistoryDetail $detail,
        array $highlights,
        array $sections,
        array $gallery,
        array $facts
    ) {
        $this->detail = $detail;
        $this->highlights = $highlights;
        $this->sections = $sections;
        $this->gallery = $gallery;
        $this->facts = $facts;
        $this->isNew = $detail === null;
    }
}
