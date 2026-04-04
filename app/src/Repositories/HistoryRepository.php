<?php

namespace App\Repositories;

use PDO;
use App\Framework\Repository;

class HistoryRepository extends Repository
{
    // Returns all highlights (without slugs) — used by the CMS
    public function getAllHighlights(): array
    {
        return $this->connection
            ->query("SELECT * FROM history_highlights ORDER BY id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Returns all highlights joined with their detail page slug — used by the front end
    public function getAllHighlightsWithSlugs(): array
    {
        $stmt = $this->connection->prepare("
            SELECT hh.*, hd.slug
            FROM history_highlights hh
            LEFT JOIN history_details hd ON hh.id = hd.highlight_id
            ORDER BY hh.id ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Returns available tickets grouped by type: ['individual' => [...], 'family' => [...]]
    public function getAvailableTickets(): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM history_tickets
            WHERE available_spots > 0
            ORDER BY ticket_type ASC, time_slot ASC
        ");
        $stmt->execute();

        $grouped = ['individual' => [], 'family' => []];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $grouped[$row['ticket_type'] ?? 'individual'][] = $row;
        }

        return $grouped;
    }

    // Returns a single content row for a given section key (e.g. 'hero', 'intro')
    public function getContentBySection(string $section): array|false
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM history_content WHERE section = :section LIMIT 1
        ");
        $stmt->execute([':section' => $section]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Returns all content rows (used by the ViewModel to organise by section key)
    public function getAllContent(): array
    {
        return $this->connection
            ->query("SELECT * FROM history_content ORDER BY id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Returns a detail page row joined with its parent highlight, looked up by URL slug
    public function getDetailBySlug(string $slug): array|false
    {
        $stmt = $this->connection->prepare("
            SELECT hd.*, hh.title AS highlight_title, hh.description AS highlight_description
            FROM history_details hd
            LEFT JOIN history_highlights hh ON hd.highlight_id = hh.id
            WHERE hd.slug = :slug
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Returns all content sections for a detail page, ordered by sort_order
    public function getDetailSections(int $detailId): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM history_detail_sections
            WHERE detail_id = :detail_id
            ORDER BY sort_order ASC
        ");
        $stmt->execute([':detail_id' => $detailId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Returns all gallery images for a detail page, ordered by sort_order
    public function getDetailGallery(int $detailId): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM history_detail_gallery
            WHERE detail_id = :detail_id
            ORDER BY sort_order ASC
        ");
        $stmt->execute([':detail_id' => $detailId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Returns all quick facts for a detail page, ordered by sort_order
    public function getDetailFacts(int $detailId): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM history_detail_facts
            WHERE detail_id = :detail_id
            ORDER BY sort_order ASC
        ");
        $stmt->execute([':detail_id' => $detailId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}