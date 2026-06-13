<?php

namespace App\Services;

use App\Models\Exceptions\EmptyFieldException;
use App\Models\History\HistoryContent;
use App\Models\History\HistoryDetail;
use App\Models\History\HistoryDetailSection;
use App\Models\History\HistoryFact;
use App\Models\History\HistoryGalleryImage;
use App\Models\History\HistoryHighlight;
use App\Repositories\HistoryCmsRepository;

class HistoryCmsService
{
    private static ?HistoryCmsService $_instance = null;

    public static function getInstance(): HistoryCmsService
    {
        if (self::$_instance === null) {
            self::$_instance = new HistoryCmsService(
                HistoryCmsRepository::getInstance(),
                FileUploadService::getInstance()
            );
        }

        return self::$_instance;
    }

    private const UPLOAD_DIR = __DIR__ . '/../../public/assets/uploads/history/';
    private const CONTENT_SECTIONS = ['hero', 'intro', 'walk', 'cta'];

    private HistoryCmsRepository $repo;
    private FileUploadService $uploads;

    private function __construct(HistoryCmsRepository $repo, FileUploadService $uploads)
    {
        $this->repo = $repo;
        $this->uploads = $uploads;
    }

    // -----------------------------------------------------------------------
    // DASHBOARD / READ
    // -----------------------------------------------------------------------

    /**
     * @return HistoryHighlight[]
     */
    public function getAllHighlights(): array
    {
        return $this->repo->getAllHighlights();
    }

    /**
     * @return array<string, HistoryContent>
     */
    public function getAllContentKeyed(): array
    {
        return $this->repo->getAllContentKeyed();
    }

    /**
     * @return HistoryDetail[]
     */
    public function getAllDetails(): array
    {
        return $this->repo->getAllDetails();
    }

    public function getIndividualPrice(): int
    {
        return $this->repo->getIndividualPrice();
    }

    public function getFamilyPrice(): int
    {
        return $this->repo->getFamilyPrice();
    }

    public function getDetailById(int $id): ?HistoryDetail
    {
        return $this->repo->getDetailById($id);
    }

    /**
     * @return HistoryDetailSection[]
     */
    public function getDetailSections(int $detailId): array
    {
        return $this->repo->getDetailSections($detailId);
    }

    /**
     * @return HistoryGalleryImage[]
     */
    public function getDetailGallery(int $detailId): array
    {
        return $this->repo->getDetailGallery($detailId);
    }

    /**
     * @return HistoryFact[]
     */
    public function getDetailFacts(int $detailId): array
    {
        return $this->repo->getDetailFacts($detailId);
    }

    // -----------------------------------------------------------------------
    // HIGHLIGHTS
    // -----------------------------------------------------------------------

    /**
     * @param array|null $imageFile $_FILES['image'] entry, or null if not uploaded
     */
    public function saveHighlight(int $id, string $title, string $description, ?array $imageFile): void
    {
        $title = trim($title);
        $description = trim($description);

        if ($title === '') {
            throw new EmptyFieldException('Title is required.');
        }

        $image = $this->resolveImage($imageFile, $id > 0 ? $this->repo->getHighlightById($id)?->image : null);

        if ($id > 0) {
            $this->repo->updateHighlight($id, $title, $description, $image);
        } else {
            $this->repo->createHighlight($title, $description, $image);
        }
    }

    public function deleteHighlight(int $id): void
    {
        $this->repo->deleteHighlight($id);
    }

    // -----------------------------------------------------------------------
    // TICKETS
    // -----------------------------------------------------------------------

    /**
     * @param int $type 0 = individual, 1 = family
     * @param float $price price in whole currency units (e.g. euros)
     */
    public function saveTicketPrice(int $type, float $price): void
    {
        if ($price < 0) {
            throw new EmptyFieldException('Price cannot be negative.');
        }

        $priceInCents = (int)round($price * 100);

        if ($type === 0) {
            $this->repo->updateIndividualPrice($priceInCents);
        } else {
            $this->repo->updateFamilyPrice($priceInCents);
        }
    }

    // -----------------------------------------------------------------------
    // PAGE CONTENT
    // -----------------------------------------------------------------------

    /**
     * @param array $post   The full $_POST array
     * @param array $files  The full $_FILES array
     */
    public function saveContent(array $post, array $files): void
    {
        foreach (self::CONTENT_SECTIONS as $section) {
            $title    = trim($post["{$section}_title"] ?? '');
            $subtitle = trim($post["{$section}_subtitle"] ?? '');

            $image    = $this->resolveImage($files["{$section}_image"] ?? null, $post["{$section}_img_current"] ?? null);
            $imgLeft  = $this->resolveImage($files["{$section}_image_left"] ?? null, $post["{$section}_img_left_current"] ?? null);
            $imgRight = $this->resolveImage($files["{$section}_image_right"] ?? null, $post["{$section}_img_right_current"] ?? null);

            $this->repo->upsertContent($section, $title, $subtitle, $image, $imgLeft, $imgRight);
        }
    }

    // -----------------------------------------------------------------------
    // DETAILS
    // -----------------------------------------------------------------------

    /**
     * @param array      $post     The full $_POST array
     * @param array|null $heroFile $_FILES['hero_image'] entry, or null
     *
     * @return int The id of the saved (created or updated) detail page.
     */
    public function saveDetail(int $id, array $post, ?array $heroFile): int
    {
        $slug = trim($post['slug'] ?? '');
        $pageTitle = trim($post['page_title'] ?? '');

        if ($slug === '' || $pageTitle === '') {
            throw new EmptyFieldException('Slug and page title are required.');
        }

        $existingImage = $id > 0 ? $this->repo->getDetailById($id)?->heroImage : null;
        $heroImage = $this->resolveImage($heroFile, $existingImage);

        $detail = new HistoryDetail(
            $id,
            (int)($post['highlight_id'] ?? 0),
            $slug,
            $pageTitle,
            $heroImage,
            trim($post['location'] ?? ''),
            trim($post['founded_year'] ?? ''),
            trim($post['style_type'] ?? ''),
            trim($post['meta_description'] ?? '')
        );

        if ($id > 0) {
            $this->repo->updateDetail($id, $detail);
            return $id;
        }

        return $this->repo->createDetail($detail);
    }

    public function deleteDetail(int $id): void
    {
        $this->repo->deleteDetail($id);
    }

    // -----------------------------------------------------------------------
    // SECTIONS
    // -----------------------------------------------------------------------

    /**
     * @param array|null $imageFile $_FILES['image_path'] entry, or null
     *
     * @return int The detail_id the section belongs to (used for redirecting).
     */
    public function saveSection(int $id, int $detailId, array $post, ?array $imageFile): int
    {
        $sectionType = trim($post['section_type'] ?? '');
        if ($sectionType === '') {
            throw new EmptyFieldException('Section type is required.');
        }

        $existingImage = $id > 0 ? $this->repo->getSectionById($id)?->imagePath : null;
        $image = $this->resolveImage($imageFile, $existingImage);

        $section = new HistoryDetailSection(
            $id,
            $detailId,
            $sectionType,
            trim($post['section_title'] ?? ''),
            trim($post['content'] ?? ''),
            $image,
            (int)($post['sort_order'] ?? 0)
        );

        if ($id > 0) {
            $this->repo->updateSection($id, $section);
        } else {
            $this->repo->createSection($section);
        }

        return $detailId;
    }

    /**
     * @return int The detail_id the section belonged to (used for redirecting).
     */
    public function deleteSection(int $id, int $detailId): int
    {
        $this->repo->deleteSection($id);

        return $detailId;
    }

    // -----------------------------------------------------------------------
    // GALLERY
    // -----------------------------------------------------------------------

    public function addGalleryImage(int $detailId, string $caption, int $sortOrder, ?array $imageFile): void
    {
        if (empty($imageFile['tmp_name'])) {
            throw new EmptyFieldException('An image is required.');
        }

        $filename = $this->uploads->uploadImage($imageFile, self::UPLOAD_DIR);
        $this->repo->createGalleryImage($detailId, $filename, trim($caption), $sortOrder);
    }

    /**
     * @return int The detail_id the image belonged to (used for redirecting).
     */
    public function deleteGalleryImage(int $id): int
    {
        $image = $this->repo->getGalleryImageById($id);
        $detailId = $image?->detailId ?? 0;

        $this->repo->deleteGalleryImage($id);

        return $detailId;
    }

    // -----------------------------------------------------------------------
    // FACTS
    // -----------------------------------------------------------------------

    /**
     * @return int The detail_id the fact belongs to (used for redirecting).
     */
    public function saveFact(int $id, int $detailId, array $post): int
    {
        $label = trim($post['label'] ?? '');
        $value = trim($post['value'] ?? '');

        if ($label === '' || $value === '') {
            throw new EmptyFieldException('Label and value are required.');
        }

        $fact = new HistoryFact(
            $id,
            $detailId,
            trim($post['icon'] ?? ''),
            $label,
            $value,
            (int)($post['sort_order'] ?? 0)
        );

        if ($id > 0) {
            $this->repo->updateFact($id, $fact);
        } else {
            $this->repo->createFact($fact);
        }

        return $detailId;
    }

    /**
     * @return int The detail_id the fact belonged to (used for redirecting).
     */
    public function deleteFact(int $id): int
    {
        $fact = $this->repo->getFactById($id);
        $detailId = $fact?->detailId ?? 0;

        $this->repo->deleteFact($id);

        return $detailId;
    }

    // -----------------------------------------------------------------------
    // HELPERS
    // -----------------------------------------------------------------------

    /**
     * If a new image was uploaded, validate & store it and return the new filename.
     * Otherwise, fall back to the existing/current filename (or null).
     *
     * @param array|null  $file           $_FILES[...] entry, or null/empty
     * @param string|null $existingImage  The current filename to keep if nothing new was uploaded
     */
    private function resolveImage(?array $file, ?string $existingImage): ?string
    {
        if (!empty($file['tmp_name'])) {
            return $this->uploads->uploadImage($file, self::UPLOAD_DIR);
        }

        return $existingImage !== '' ? $existingImage : null;
    }
}
