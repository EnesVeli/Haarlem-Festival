<?php
namespace App\Interfaces;

use App\Models\CmsContent;

/**
 * Contract for the Stories homepage content repository.
 */
interface IStoriesHomepageRepository
{
    /**
     * Fetch a CMS_Content row by its slug.
     *
     * @param  string $slug  The unique slug (e.g. 'stories')
     * @return CmsContent|null
     */
    public function getBySlug(string $slug): ?CmsContent;

    /**
     * Update a CMS_Content row identified by slug.
     *
     * @param  string $slug  The unique slug to update
     * @param  array  $data  Associative array of column => value pairs
     * @return void
     */
    public function updateBySlug(string $slug, array $data): void;
}
