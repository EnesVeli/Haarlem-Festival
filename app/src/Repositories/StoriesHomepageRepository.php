<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Interfaces\IStoriesHomepageRepository;
use App\Models\CmsContent;
use PDO;

/**
 * StoriesHomepageRepository — reads and writes CMS_Content rows for the Stories homepage.
 *
 * Extends the base Repository (provides $this->connection PDO).
 * Implements IStoriesHomepageRepository.
 */
class StoriesHomepageRepository extends Repository implements IStoriesHomepageRepository
{
    /**
     * Fetch a CMS_Content row by slug.
     *
     * @param  string $slug  The unique slug (e.g. 'stories')
     * @return CmsContent|null  The content row or null
     */
    public function getBySlug(string $slug): ?CmsContent
    {
        $stmt = $this->connection->prepare(
            "SELECT content_id, slug, title, subtitle, body_html, image_path, quote_text, cta_text,
                    ticket_info_title_1, ticket_info_body_1, ticket_info_note_1,
                    ticket_info_title_2, ticket_info_body_2, cta_description
               FROM `CMS_Content`
              WHERE slug = :slug
              LIMIT 1"
        );
        $stmt->execute([':slug' => $slug]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, CmsContent::class);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Update a CMS_Content row identified by its slug.
     *
     * Only updates the columns provided in $data.
     *
     * @param  string $slug  The unique slug to target
     * @param  array  $data  Associative array [column => value]
     * @return void
     */
    public function updateBySlug(string $slug, array $data): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE `CMS_Content`
                SET title      = :title,
                    subtitle   = :subtitle,
                    body_html  = :body_html,
                    image_path = :image_path,
                    quote_text = :quote_text,
                    cta_text   = :cta_text,
                    ticket_info_title_1 = :ticket_info_title_1,
                    ticket_info_body_1  = :ticket_info_body_1,
                    ticket_info_note_1  = :ticket_info_note_1,
                    ticket_info_title_2 = :ticket_info_title_2,
                    ticket_info_body_2  = :ticket_info_body_2,
                    cta_description     = :cta_description
              WHERE slug = :slug"
        );

        $stmt->execute([
            ':title'      => $data['title']      ?? '',
            ':subtitle'   => $data['subtitle']    ?? null,
            ':body_html'  => $data['body_html']   ?? null,
            ':image_path' => $data['image_path']  ?? null,
            ':quote_text' => $data['quote_text']  ?? null,
            ':cta_text'   => $data['cta_text']    ?? null,
            ':ticket_info_title_1' => $data['ticket_info_title_1'] ?? null,
            ':ticket_info_body_1'  => $data['ticket_info_body_1'] ?? null,
            ':ticket_info_note_1'  => $data['ticket_info_note_1'] ?? null,
            ':ticket_info_title_2' => $data['ticket_info_title_2'] ?? null,
            ':ticket_info_body_2'  => $data['ticket_info_body_2'] ?? null,
            ':cta_description'     => $data['cta_description'] ?? null,
            ':slug'       => $slug,
        ]);
    }
}