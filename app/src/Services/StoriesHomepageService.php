<?php
namespace App\Services;

use App\Interfaces\IStoriesHomepageRepository;
use App\Interfaces\IStoriesHomepageService;
use App\Models\CmsContent;

/**
 * StoriesHomepageService — business logic for the Stories homepage CMS content.
 *
 * Implements IStoriesHomepageService.
 * Receives the repository via its interface type hint (dependency injection).
 */
class StoriesHomepageService implements IStoriesHomepageService
{
    /** @var IStoriesHomepageRepository */
    private IStoriesHomepageRepository $repository;

    /**
     * @param IStoriesHomepageRepository $repository
     */
    public function __construct(IStoriesHomepageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve the Stories homepage CMS content.
     *
     * @return CmsContent|null
     */
    public function getStoriesContent(): ?CmsContent
    {
        return $this->repository->getBySlug('stories');
    }

    /**
     * Sanitise and persist Stories homepage CMS content.
     *
     * All plain-text fields are stripped of HTML tags.
     * body_html is kept raw (WYSIWYG output).
     *
     * @param  array $data  Raw form data
     * @return void
     */
    public function saveStoriesContent(array $data): void
    {
        $clean = [
            'title'      => strip_tags($data['title']      ?? ''),
            'subtitle'   => strip_tags($data['subtitle']   ?? ''),
            'body_html'  => $data['body_html'] ?? '',          // keep raw HTML
            'image_path' => strip_tags($data['image_path']  ?? ''),
            'quote_text' => strip_tags($data['quote_text']  ?? ''),
            'cta_text'   => strip_tags($data['cta_text']    ?? ''),
        ];

        $this->repository->updateBySlug('stories', $clean);
    }
}
