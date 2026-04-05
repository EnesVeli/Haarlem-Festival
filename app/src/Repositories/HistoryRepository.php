<?php

namespace App\Repositories;

use PDO;
use App\Framework\Repository;

class HistoryRepository extends Repository
{
    public function getAllHighlights()
    {
        $sql = "SELECT * FROM history_highlights ORDER BY id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableTickets()
    {
        $sql = "SELECT * FROM history_tickets WHERE available_spots > 0 ORDER BY time_slot ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContentBySection($section)
    {
        $sql = "SELECT * FROM history_content WHERE section = :section LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContent()
    {
        $sql = "SELECT * FROM history_content ORDER BY id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get detail page by slug
     */
    public function getDetailBySlug($slug)
    {
        $sql = "SELECT hd.*, hh.title as highlight_title, hh.description as highlight_description
                FROM history_details hd
                LEFT JOIN history_highlights hh ON hd.highlight_id = hh.id
                WHERE hd.slug = :slug
                LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all sections for a detail page
     */
    public function getDetailSections($detailId)
    {
        $sql = "SELECT * FROM history_detail_sections 
                WHERE detail_id = :detail_id 
                ORDER BY sort_order ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':detail_id', $detailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get gallery images for a detail page
     */
    public function getDetailGallery($detailId)
    {
        $sql = "SELECT * FROM history_detail_gallery 
                WHERE detail_id = :detail_id 
                ORDER BY sort_order ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':detail_id', $detailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get quick facts for a detail page
     */
    public function getDetailFacts($detailId)
    {
        $sql = "SELECT * FROM history_detail_facts 
                WHERE detail_id = :detail_id 
                ORDER BY sort_order ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':detail_id', $detailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get highlight by ID with slug
     */
    public function getHighlightWithSlug($highlightId)
    {
        $sql = "SELECT hh.*, hd.slug 
                FROM history_highlights hh
                LEFT JOIN history_details hd ON hh.id = hd.highlight_id
                WHERE hh.id = :id
                LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $highlightId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    /**
     * Get all highlights with their slugs for linking
     */
    public function getAllHighlightsWithSlugs()
    {
        $sql = "SELECT hh.*, hd.slug 
                FROM history_highlights hh
                LEFT JOIN history_details hd ON hh.id = hd.highlight_id
                ORDER BY hh.id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}