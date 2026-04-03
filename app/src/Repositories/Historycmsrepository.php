<?php

namespace App\Repositories;

use PDO;
use App\Framework\Repository;

class HistoryCmsRepository extends Repository
{
    // -----------------------------------------------------------------------
    // HIGHLIGHTS
    // -----------------------------------------------------------------------

    public function getAllHighlights(): array
    {
        return $this->connection
            ->query("SELECT * FROM history_highlights ORDER BY id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHighlightById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_highlights WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createHighlight(string $title, string $description, ?string $image): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO history_highlights (title, description, image) VALUES (:title, :description, :image)"
        );
        $stmt->execute([':title' => $title, ':description' => $description, ':image' => $image]);
    }

    public function updateHighlight(int $id, string $title, string $description, ?string $image): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE history_highlights SET title=:title, description=:description, image=:image WHERE id=:id"
        );
        $stmt->execute([':title' => $title, ':description' => $description, ':image' => $image, ':id' => $id]);
    }

    public function deleteHighlight(int $id): void
    {
        $this->connection->prepare("DELETE FROM history_highlights WHERE id=:id")->execute([':id' => $id]);
    }

    // -----------------------------------------------------------------------
    // PAGE CONTENT
    // -----------------------------------------------------------------------

    public function getAllContent(): array
    {
        return $this->connection
            ->query("SELECT * FROM history_content ORDER BY id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllContentKeyed(): array
    {
        $rows = $this->getAllContent();
        $keyed = [];
        foreach ($rows as $row) {
            $keyed[$row['section']] = $row;
        }
        return $keyed;
    }

    public function getContentBySection(string $section): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_content WHERE section=:s LIMIT 1");
        $stmt->execute([':s' => $section]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function upsertContent(string $section, string $title, string $subtitle, ?string $image, ?string $imgLeft, ?string $imgRight): void
    {
        $stmt = $this->connection->prepare("SELECT id FROM history_content WHERE section=:section LIMIT 1");
        $stmt->execute([':section' => $section]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $sql = "UPDATE history_content 
                    SET title=:title, subtitle=:subtitle, image=:image, image_left=:image_left, image_right=:image_right
                    WHERE section=:section";
        } else {
            $sql = "INSERT INTO history_content (section, title, subtitle, image, image_left, image_right)
                    VALUES (:section, :title, :subtitle, :image, :image_left, :image_right)";
        }
        $this->connection->prepare($sql)->execute([
            ':section'     => $section,
            ':title'       => $title,
            ':subtitle'    => $subtitle,
            ':image'       => $image,
            ':image_left'  => $imgLeft,
            ':image_right' => $imgRight,
        ]);
    }

    // -----------------------------------------------------------------------
    // TICKETS
    // -----------------------------------------------------------------------

    public function getAllTickets(): array
    {
        return $this->connection
            ->query("SELECT * FROM history_tickets ORDER BY id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_tickets WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createTicket(string $timeSlot, float $price, int $spots): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO history_tickets (time_slot, price, available_spots) VALUES (:ts, :price, :spots)"
        );
        $stmt->execute([':ts' => $timeSlot, ':price' => $price, ':spots' => $spots]);
    }

    public function updateTicket(int $id, string $timeSlot, float $price, int $spots): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE history_tickets SET time_slot=:ts, price=:price, available_spots=:spots WHERE id=:id"
        );
        $stmt->execute([':ts' => $timeSlot, ':price' => $price, ':spots' => $spots, ':id' => $id]);
    }

    public function deleteTicket(int $id): void
    {
        $this->connection->prepare("DELETE FROM history_tickets WHERE id=:id")->execute([':id' => $id]);
    }

    // -----------------------------------------------------------------------
    // DETAILS
    // -----------------------------------------------------------------------

    public function getAllDetails(): array
    {
        return $this->connection
            ->query("SELECT hd.*, hh.title as highlight_title 
                     FROM history_details hd
                     LEFT JOIN history_highlights hh ON hd.highlight_id = hh.id
                     ORDER BY hd.id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_details WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createDetail(array $data): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO history_details (highlight_id, slug, page_title, hero_image, location, founded_year, style_type, meta_description)
             VALUES (:highlight_id, :slug, :page_title, :hero_image, :location, :founded_year, :style_type, :meta_description)"
        );
        $stmt->execute([
            ':highlight_id'     => $data['highlight_id'],
            ':slug'             => $data['slug'],
            ':page_title'       => $data['page_title'],
            ':hero_image'       => $data['hero_image'],
            ':location'         => $data['location'],
            ':founded_year'     => $data['founded_year'],
            ':style_type'       => $data['style_type'],
            ':meta_description' => $data['meta_description'],
        ]);
        return (int)$this->connection->lastInsertId();
    }

    public function updateDetail(int $id, array $data): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE history_details SET highlight_id=:highlight_id, slug=:slug, page_title=:page_title,
             hero_image=:hero_image, location=:location, founded_year=:founded_year,
             style_type=:style_type, meta_description=:meta_description WHERE id=:id"
        );
        $stmt->execute([
            ':highlight_id'     => $data['highlight_id'],
            ':slug'             => $data['slug'],
            ':page_title'       => $data['page_title'],
            ':hero_image'       => $data['hero_image'],
            ':location'         => $data['location'],
            ':founded_year'     => $data['founded_year'],
            ':style_type'       => $data['style_type'],
            ':meta_description' => $data['meta_description'],
            ':id'               => $id,
        ]);
    }

    public function deleteDetail(int $id): void
    {
        $this->connection->prepare("DELETE FROM history_details WHERE id=:id")->execute([':id' => $id]);
    }

    // -----------------------------------------------------------------------
    // SECTIONS
    // -----------------------------------------------------------------------

    public function getDetailSections(int $detailId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM history_detail_sections WHERE detail_id=:id ORDER BY sort_order ASC"
        );
        $stmt->execute([':id' => $detailId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSectionById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_detail_sections WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createSection(array $data): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO history_detail_sections (detail_id, section_type, section_title, content, image_path, sort_order)
             VALUES (:detail_id, :section_type, :section_title, :content, :image_path, :sort_order)"
        );
        $stmt->execute($data);
    }

    public function updateSection(int $id, array $data): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE history_detail_sections SET section_type=:section_type, section_title=:section_title,
             content=:content, image_path=:image_path, sort_order=:sort_order WHERE id=:id"
        );
        $data[':id'] = $id;
        $stmt->execute($data);
    }

    public function deleteSection(int $id): void
    {
        $this->connection->prepare("DELETE FROM history_detail_sections WHERE id=:id")->execute([':id' => $id]);
    }

    // -----------------------------------------------------------------------
    // GALLERY
    // -----------------------------------------------------------------------

    public function getDetailGallery(int $detailId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM history_detail_gallery WHERE detail_id=:id ORDER BY sort_order ASC"
        );
        $stmt->execute([':id' => $detailId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGalleryImageById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_detail_gallery WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createGalleryImage(int $detailId, string $imagePath, string $caption, int $sortOrder): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO history_detail_gallery (detail_id, image_path, caption, sort_order)
             VALUES (:detail_id, :image_path, :caption, :sort_order)"
        );
        $stmt->execute([':detail_id' => $detailId, ':image_path' => $imagePath, ':caption' => $caption, ':sort_order' => $sortOrder]);
    }

    public function deleteGalleryImage(int $id): void
    {
        $this->connection->prepare("DELETE FROM history_detail_gallery WHERE id=:id")->execute([':id' => $id]);
    }

    // -----------------------------------------------------------------------
    // FACTS
    // -----------------------------------------------------------------------

    public function getDetailFacts(int $detailId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM history_detail_facts WHERE detail_id=:id ORDER BY sort_order ASC"
        );
        $stmt->execute([':id' => $detailId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFactById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM history_detail_facts WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createFact(array $data): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO history_detail_facts (detail_id, icon, label, value, sort_order)
             VALUES (:detail_id, :icon, :label, :value, :sort_order)"
        );
        $stmt->execute($data);
    }

    public function updateFact(int $id, array $data): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE history_detail_facts SET icon=:icon, label=:label, value=:value, sort_order=:sort_order WHERE id=:id"
        );
        $data[':id'] = $id;
        $stmt->execute($data);
    }

    public function deleteFact(int $id): void
    {
        $this->connection->prepare("DELETE FROM history_detail_facts WHERE id=:id")->execute([':id' => $id]);
    }
}