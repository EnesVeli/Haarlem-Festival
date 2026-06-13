<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\History\HistoryContent;
use App\Models\History\HistoryDetail;
use App\Models\History\HistoryDetailSection;
use App\Models\History\HistoryFact;
use App\Models\History\HistoryGalleryImage;
use App\Models\History\HistoryHighlight;
use App\Models\Exceptions\QueryExecutionException;
use PDO;
use PDOException;

class HistoryCmsRepository extends Repository
{
    private static ?HistoryCmsRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): HistoryCmsRepository
    {
        if (self::$_instance === null) {
            self::$_instance = new HistoryCmsRepository();
        }

        return self::$_instance;
    }

    // -----------------------------------------------------------------------
    // HIGHLIGHTS
    // -----------------------------------------------------------------------

    /**
     * @return HistoryHighlight[]
     */
    public function getAllHighlights(): array
    {
        try {
            $rows = $this->connection
                ->query("SELECT id, title, description, image FROM history_highlights ORDER BY id ASC")
                ->fetchAll(PDO::FETCH_ASSOC);

            return array_map([HistoryHighlight::class, 'fromRow'], $rows);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load highlights.', 0, $e);
        }
    }

    public function getHighlightById(int $id): ?HistoryHighlight
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, title, description, image FROM history_highlights WHERE id = :id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? HistoryHighlight::fromRow($row) : null;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load highlight.', 0, $e);
        }
    }

    public function createHighlight(string $title, string $description, ?string $image): void
    {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO history_highlights (title, description, image) VALUES (:title, :description, :image)"
            );
            $stmt->execute([':title' => $title, ':description' => $description, ':image' => $image]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to create highlight.', 0, $e);
        }
    }

    public function updateHighlight(int $id, string $title, string $description, ?string $image): void
    {
        try {
            $stmt = $this->connection->prepare(
                "UPDATE history_highlights SET title=:title, description=:description, image=:image WHERE id=:id"
            );
            $stmt->execute([':title' => $title, ':description' => $description, ':image' => $image, ':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to update highlight.', 0, $e);
        }
    }

    public function deleteHighlight(int $id): void
    {
        try {
            $this->connection->prepare("DELETE FROM history_highlights WHERE id=:id")->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to delete highlight.', 0, $e);
        }
    }

    // -----------------------------------------------------------------------
    // PAGE CONTENT
    // -----------------------------------------------------------------------

    /**
     * @return HistoryContent[]
     */
    public function getAllContent(): array
    {
        try {
            $rows = $this->connection
                ->query("SELECT id, section, title, subtitle, image, image_left, image_right FROM history_content ORDER BY id ASC")
                ->fetchAll(PDO::FETCH_ASSOC);

            return array_map([HistoryContent::class, 'fromRow'], $rows);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load page content.', 0, $e);
        }
    }

    /**
     * @return array<string, HistoryContent> keyed by section name
     */
    public function getAllContentKeyed(): array
    {
        $keyed = [];
        foreach ($this->getAllContent() as $content) {
            $keyed[$content->section] = $content;
        }

        return $keyed;
    }

    public function upsertContent(
        string $section,
        string $title,
        string $subtitle,
        ?string $image,
        ?string $imgLeft,
        ?string $imgRight
    ): void {
        try {
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
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to save page content.', 0, $e);
        }
    }

    // -----------------------------------------------------------------------
    // DETAILS
    // -----------------------------------------------------------------------

    /**
     * @return HistoryDetail[]
     */
    public function getAllDetails(): array
    {
        try {
            $rows = $this->connection
                ->query(
                    "SELECT hd.id, hd.highlight_id, hd.slug, hd.page_title, hd.hero_image, hd.location,
                            hd.founded_year, hd.style_type, hd.meta_description, hh.title AS highlight_title
                     FROM history_details hd
                     LEFT JOIN history_highlights hh ON hd.highlight_id = hh.id
                     ORDER BY hd.id ASC"
                )
                ->fetchAll(PDO::FETCH_ASSOC);

            return array_map([HistoryDetail::class, 'fromRow'], $rows);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load detail pages.', 0, $e);
        }
    }

    public function getDetailById(int $id): ?HistoryDetail
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, highlight_id, slug, page_title, hero_image, location, founded_year, style_type, meta_description
                 FROM history_details WHERE id=:id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? HistoryDetail::fromRow($row) : null;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load detail page.', 0, $e);
        }
    }

    public function createDetail(HistoryDetail $detail): int
    {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO history_details (highlight_id, slug, page_title, hero_image, location, founded_year, style_type, meta_description)
                 VALUES (:highlight_id, :slug, :page_title, :hero_image, :location, :founded_year, :style_type, :meta_description)"
            );
            $stmt->execute($detail->toParams());

            return (int)$this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to create detail page.', 0, $e);
        }
    }

    public function updateDetail(int $id, HistoryDetail $detail): void
    {
        try {
            $stmt = $this->connection->prepare(
                "UPDATE history_details SET highlight_id=:highlight_id, slug=:slug, page_title=:page_title,
                 hero_image=:hero_image, location=:location, founded_year=:founded_year,
                 style_type=:style_type, meta_description=:meta_description WHERE id=:id"
            );
            $stmt->execute($detail->toParams() + [':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to update detail page.', 0, $e);
        }
    }

    public function deleteDetail(int $id): void
    {
        try {
            $this->connection->prepare("DELETE FROM history_details WHERE id=:id")->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to delete detail page.', 0, $e);
        }
    }

    // -----------------------------------------------------------------------
    // SECTIONS
    // -----------------------------------------------------------------------

    /**
     * @return HistoryDetailSection[]
     */
    public function getDetailSections(int $detailId): array
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, detail_id, section_type, section_title, content, image_path, sort_order
                 FROM history_detail_sections WHERE detail_id=:id ORDER BY sort_order ASC"
            );
            $stmt->execute([':id' => $detailId]);

            return array_map([HistoryDetailSection::class, 'fromRow'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load sections.', 0, $e);
        }
    }

    public function getSectionById(int $id): ?HistoryDetailSection
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, detail_id, section_type, section_title, content, image_path, sort_order
                 FROM history_detail_sections WHERE id=:id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? HistoryDetailSection::fromRow($row) : null;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load section.', 0, $e);
        }
    }

    public function createSection(HistoryDetailSection $section): void
    {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO history_detail_sections (detail_id, section_type, section_title, content, image_path, sort_order)
                 VALUES (:detail_id, :section_type, :section_title, :content, :image_path, :sort_order)"
            );
            $stmt->execute($section->toParams());
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to create section.', 0, $e);
        }
    }

    public function updateSection(int $id, HistoryDetailSection $section): void
    {
        try {
            $stmt = $this->connection->prepare(
                "UPDATE history_detail_sections SET section_type=:section_type, section_title=:section_title,
                 content=:content, image_path=:image_path, sort_order=:sort_order WHERE id=:id"
            );
            $stmt->execute($section->toParams() + [':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to update section.', 0, $e);
        }
    }

    public function deleteSection(int $id): void
    {
        try {
            $this->connection->prepare("DELETE FROM history_detail_sections WHERE id=:id")->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to delete section.', 0, $e);
        }
    }

    // -----------------------------------------------------------------------
    // GALLERY
    // -----------------------------------------------------------------------

    /**
     * @return HistoryGalleryImage[]
     */
    public function getDetailGallery(int $detailId): array
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, detail_id, image_path, caption, sort_order
                 FROM history_detail_gallery WHERE detail_id=:id ORDER BY sort_order ASC"
            );
            $stmt->execute([':id' => $detailId]);

            return array_map([HistoryGalleryImage::class, 'fromRow'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load gallery.', 0, $e);
        }
    }

    public function getGalleryImageById(int $id): ?HistoryGalleryImage
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, detail_id, image_path, caption, sort_order FROM history_detail_gallery WHERE id=:id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? HistoryGalleryImage::fromRow($row) : null;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load gallery image.', 0, $e);
        }
    }

    public function createGalleryImage(int $detailId, string $imagePath, string $caption, int $sortOrder): void
    {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO history_detail_gallery (detail_id, image_path, caption, sort_order)
                 VALUES (:detail_id, :image_path, :caption, :sort_order)"
            );
            $stmt->execute([
                ':detail_id'  => $detailId,
                ':image_path' => $imagePath,
                ':caption'    => $caption,
                ':sort_order' => $sortOrder,
            ]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to add gallery image.', 0, $e);
        }
    }

    public function deleteGalleryImage(int $id): void
    {
        try {
            $this->connection->prepare("DELETE FROM history_detail_gallery WHERE id=:id")->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to delete gallery image.', 0, $e);
        }
    }

    // -----------------------------------------------------------------------
    // FACTS
    // -----------------------------------------------------------------------

    /**
     * @return HistoryFact[]
     */
    public function getDetailFacts(int $detailId): array
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, detail_id, icon, label, value, sort_order
                 FROM history_detail_facts WHERE detail_id=:id ORDER BY sort_order ASC"
            );
            $stmt->execute([':id' => $detailId]);

            return array_map([HistoryFact::class, 'fromRow'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load facts.', 0, $e);
        }
    }

    public function getFactById(int $id): ?HistoryFact
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id, detail_id, icon, label, value, sort_order FROM history_detail_facts WHERE id=:id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? HistoryFact::fromRow($row) : null;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load fact.', 0, $e);
        }
    }

    public function createFact(HistoryFact $fact): void
    {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO history_detail_facts (detail_id, icon, label, value, sort_order)
                 VALUES (:detail_id, :icon, :label, :value, :sort_order)"
            );
            $stmt->execute($fact->toParams());
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to create fact.', 0, $e);
        }
    }

    public function updateFact(int $id, HistoryFact $fact): void
    {
        try {
            $stmt = $this->connection->prepare(
                "UPDATE history_detail_facts SET icon=:icon, label=:label, value=:value, sort_order=:sort_order WHERE id=:id"
            );
            $stmt->execute($fact->toParams() + [':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to update fact.', 0, $e);
        }
    }

    public function deleteFact(int $id): void
    {
        try {
            $this->connection->prepare("DELETE FROM history_detail_facts WHERE id=:id")->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to delete fact.', 0, $e);
        }
    }

    // -----------------------------------------------------------------------
    // TICKETS
    // -----------------------------------------------------------------------

    public function getIndividualPrice(): int
    {
        try {
            $stmt = $this->connection->prepare("SELECT individual_price FROM HistoryCMS WHERE id = 1");
            $stmt->execute();
            $price = $stmt->fetchColumn();

            return $price !== false ? (int)$price : 0;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load individual ticket price.', 0, $e);
        }
    }

    public function getFamilyPrice(): int
    {
        try {
            $stmt = $this->connection->prepare("SELECT family_price FROM HistoryCMS WHERE id = 1");
            $stmt->execute();
            $price = $stmt->fetchColumn();

            return $price !== false ? (int)$price : 0;
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to load family ticket price.', 0, $e);
        }
    }

    public function updateIndividualPrice(int $individualPriceCents): void
    {
        try {
            $stmt = $this->connection->prepare("UPDATE HistoryCMS SET individual_price=:individual_price WHERE id = 1");
            $stmt->execute([':individual_price' => $individualPriceCents]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to update individual ticket price.', 0, $e);
        }
    }

    public function updateFamilyPrice(int $familyPriceCents): void
    {
        try {
            $stmt = $this->connection->prepare("UPDATE HistoryCMS SET family_price=:family_price WHERE id = 1");
            $stmt->execute([':family_price' => $familyPriceCents]);
        } catch (PDOException $e) {
            throw new QueryExecutionException('Failed to update family ticket price.', 0, $e);
        }
    }
}
