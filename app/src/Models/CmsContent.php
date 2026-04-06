<?php
namespace App\Models;

/**
 * CmsContent model — represents a row from the CMS_Content table.
 *
 * Plain data-shape class with public typed properties.
 * Used by the Stories homepage CMS feature.
 */
class CmsContent
{
    /** @var int Primary key. */
    public int $content_id;

    /** @var string Unique slug identifier (e.g. 'stories'). */
    public string $slug;

    /** @var string Page title. */
    public string $title;

    /** @var string|null Subtitle shown below the title. */
    public ?string $subtitle;

    /** @var string|null WYSIWYG HTML body content. */
    public ?string $body_html;

    /** @var string|null Path to the hero/header image. */
    public ?string $image_path;

    /** @var string|null Inspirational quote text. */
    public ?string $quote_text;

    /** @var string|null Call-to-action text. */
    public ?string $cta_text;

    /** @var string|null Ticket info card 1 title. */
    public ?string $ticket_info_title_1;

    /** @var string|null Ticket info card 1 body. */
    public ?string $ticket_info_body_1;

    /** @var string|null Ticket info card 1 note text. */
    public ?string $ticket_info_note_1;

    /** @var string|null Ticket info card 2 title. */
    public ?string $ticket_info_title_2;

    /** @var string|null Ticket info card 2 body. */
    public ?string $ticket_info_body_2;

    /** @var string|null CTA description text shown under CTA heading. */
    public ?string $cta_description;
}
