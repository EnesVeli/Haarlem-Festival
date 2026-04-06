<?php
namespace App\Interfaces;

use App\Models\CmsContent;

/**
 * Contract for the Stories homepage content service.
 */
interface IStoriesHomepageService
{
    /**
     * Retrieve the Stories homepage CMS content.
     *
     * @return CmsContent|null
     */
    public function getStoriesContent(): ?CmsContent;

    /**
     * Save updated Stories homepage CMS content.
     *
     * @param  array $data  Form data to persist
     * @return void
     */
    public function saveStoriesContent(array $data): void;
}
