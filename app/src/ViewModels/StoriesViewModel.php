<?php
namespace App\ViewModels;

use App\Models\CmsContent;

/**
 * ViewModel for the public Stories homepage.
 *
 * Holds event program data, filter options, and CMS-editable content.
 */
class StoriesViewModel
{
    /** @var array Program events grouped by day. */
    public array $program = [];

    /** @var array Available language filter values. */
    public array $filterLanguages = [];

    /** @var array Available story-type filter values. */
    public array $filterTypes = [];

    /** @var array Available age-group filter values. */
    public array $filterAges = [];

    /** @var string Page title (may be overridden by CMS). */
    public string $pageTitle = "Stories in Haarlem";

    /** @var string Default hero description text. */
    public string $heroText = "During the last weekend of July, the streets of Haarlem transform into a living library. Stories in Haarlem brings a mix of live performances, intimate podcast recordings, and immersive family shows to unique locations across the city.";

    /** @var string Error message for the view. */
    public string $errorMessage = '';

    /** @var CmsContent|null CMS-editable homepage content (title, subtitle, body, quote, cta, image). */
    public ?CmsContent $homepageContent = null;
}