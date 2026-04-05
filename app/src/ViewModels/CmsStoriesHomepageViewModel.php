<?php
namespace App\ViewModels;

use App\Models\CmsContent;

/**
 * ViewModel for the CMS Stories Homepage editor form.
 *
 * Carries exactly the data the cms/stories/homepage view needs.
 */
class CmsStoriesHomepageViewModel
{
    /** @var CmsContent|null The current CMS content row, null on first load if missing. */
    public ?CmsContent $content;

    /** @var string Page title for the browser tab. */
    public string $pageTitle = 'Edit Stories Homepage – CMS';

    /** @var string CSRF token for the form. */
    public string $csrfToken = '';

    /** @var string|null Success flash message. */
    public ?string $success = null;

    /** @var string|null Error flash message. */
    public ?string $error = null;

    /**
     * @param CmsContent|null $content   Existing content or null
     * @param string          $csrfToken CSRF token
     * @param string|null     $success   Flash success message
     * @param string|null     $error     Flash error message
     */
    public function __construct(
        ?CmsContent $content = null,
        string $csrfToken = '',
        ?string $success = null,
        ?string $error = null
    ) {
        $this->content   = $content;
        $this->csrfToken = $csrfToken;
        $this->success   = $success;
        $this->error     = $error;
    }
}
