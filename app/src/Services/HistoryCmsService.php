<?php

namespace App\Services;

use App\Repositories\HistoryCmsRepository;

class HistoryCmsService
{
    private const TICKET_TYPE_INDIVIDUAL = 0;
    private const TICKET_TYPE_FAMILY = 1;

    private static ?HistoryCmsService $_instance = null;
    private HistoryCmsRepository $repository;

    public static function getInstance(): HistoryCmsService
    {
        if (self::$_instance === null) {
            self::$_instance = new HistoryCmsService(HistoryCmsRepository::getInstance());
        }

        return self::$_instance;
    }

    private function __construct(HistoryCmsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllHighlights(): array
    {
        return $this->repository->getAllHighlights();
    }

    public function getHighlightById(int $id): ?array
    {
        return $this->repository->getHighlightById($id);
    }

    public function saveHighlight(?int $id, string $title, string $description, ?string $image): void
    {
        if ($id !== null && $id > 0) {
            $this->repository->updateHighlight($id, $title, $description, $image);
            return;
        }

        $this->repository->createHighlight($title, $description, $image);
    }

    public function deleteHighlight(int $id): void
    {
        $this->repository->deleteHighlight($id);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getAllContentKeyed(): array
    {
        return $this->repository->getAllContentKeyed();
    }

    public function saveContentSection(
        string $section,
        string $title,
        string $subtitle,
        ?string $image,
        ?string $imageLeft,
        ?string $imageRight
    ): void {
        $this->repository->upsertContent($section, $title, $subtitle, $image, $imageLeft, $imageRight);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllDetails(): array
    {
        return $this->repository->getAllDetails();
    }

    public function getDetailById(int $id): ?array
    {
        return $this->repository->getDetailById($id);
    }

    public function saveDetail(?int $id, array $data): int
    {
        if ($id !== null && $id > 0) {
            $this->repository->updateDetail($id, $data);
            return $id;
        }

        return $this->repository->createDetail($data);
    }

    public function deleteDetail(int $id): void
    {
        $this->repository->deleteDetail($id);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDetailSections(int $detailId): array
    {
        return $this->repository->getDetailSections($detailId);
    }

    public function getSectionById(int $id): ?array
    {
        return $this->repository->getSectionById($id);
    }

    public function saveSection(?int $id, array $data): void
    {
        if ($id !== null && $id > 0) {
            $this->repository->updateSection($id, $data);
            return;
        }

        $this->repository->createSection($data);
    }

    public function deleteSection(int $id): void
    {
        $this->repository->deleteSection($id);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDetailGallery(int $detailId): array
    {
        return $this->repository->getDetailGallery($detailId);
    }

    public function getGalleryImageById(int $id): ?array
    {
        return $this->repository->getGalleryImageById($id);
    }

    public function addGalleryImage(int $detailId, string $imagePath, string $caption, int $sortOrder): void
    {
        $this->repository->createGalleryImage($detailId, $imagePath, $caption, $sortOrder);
    }

    public function deleteGalleryImage(int $id): void
    {
        $this->repository->deleteGalleryImage($id);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDetailFacts(int $detailId): array
    {
        return $this->repository->getDetailFacts($detailId);
    }

    public function getFactById(int $id): ?array
    {
        return $this->repository->getFactById($id);
    }

    public function saveFact(?int $id, array $data): void
    {
        if ($id !== null && $id > 0) {
            $this->repository->updateFact($id, $data);
            return;
        }

        $this->repository->createFact($data);
    }

    public function deleteFact(int $id): void
    {
        $this->repository->deleteFact($id);
    }

    public function getIndividualPrice(): int
    {
        return $this->repository->getIndividualPrice();
    }

    public function getFamilyPrice(): int
    {
        return $this->repository->getFamilyPrice();
    }

    public function updateTicketPrice(int $type, int $priceInCents): void
    {
        if ($type === self::TICKET_TYPE_INDIVIDUAL) {
            $this->repository->updateIndividualPrice($priceInCents);
            return;
        }

        $this->repository->updateFamilyPrice($priceInCents);
    }
}
