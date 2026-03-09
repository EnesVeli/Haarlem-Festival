<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class CmsContentRepository extends Repository
{
    public function getBlocks(string $pageKey, string $blockType, bool $onlyActive = true): array
    {
        $sql = "
            SELECT id, page_key, block_type, performer_id, title, subtitle, body, url, image_path, sort_order, is_active
            FROM cms_content
            WHERE page_key = :page_key
              AND block_type = :block_type
        ";

        if ($onlyActive) {
            $sql .= " AND is_active = 1 ";
        }

        $sql .= " ORDER BY sort_order ASC, id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':page_key' => $pageKey,
            ':block_type' => $blockType,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // For performer blocks: same blocks but also show performer name from jazz_performers
    public function getBlocksWithPerformerName(string $pageKey, string $blockType, bool $onlyActive = true): array
    {
        $sql = "
            SELECT c.id, c.page_key, c.block_type, c.performer_id,
                   c.title, c.subtitle, c.body, c.url, c.image_path, c.sort_order, c.is_active,
                   p.name AS performer_name
            FROM cms_content c
            LEFT JOIN jazz_performers p ON p.id = c.performer_id
            WHERE c.page_key = :page_key
              AND c.block_type = :block_type
        ";

        if ($onlyActive) {
            $sql .= " AND c.is_active = 1 ";
        }

        $sql .= " ORDER BY c.sort_order ASC, c.id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':page_key' => $pageKey,
            ':block_type' => $blockType,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM cms_content WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO cms_content (page_key, block_type, performer_id, title, subtitle, body, url, image_path, sort_order, is_active)
            VALUES (:page_key, :block_type, :performer_id, :title, :subtitle, :body, :url, :image_path, :sort_order, :is_active)
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':page_key'     => $data['page_key'],
            ':block_type'   => $data['block_type'],
            ':performer_id' => $data['performer_id'] ?? 0,
            ':title'        => $data['title'] ?? '',
            ':subtitle'     => $data['subtitle'] ?? null,
            ':body'         => $data['body'] ?? null,
            ':url'          => $data['url'] ?? null,
            ':image_path'   => $data['image_path'] ?? null,
            ':sort_order'   => $data['sort_order'] ?? 0,
            ':is_active'    => $data['is_active'] ?? 1,
        ]);

        return (int)$this->connection->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sql = "
            UPDATE cms_content
            SET performer_id = :performer_id,
                title = :title,
                subtitle = :subtitle,
                body = :body,
                url = :url,
                image_path = :image_path,
                sort_order = :sort_order,
                is_active = :is_active
            WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':id'           => $id,
            ':performer_id' => $data['performer_id'] ?? 0,
            ':title'        => $data['title'] ?? '',
            ':subtitle'     => $data['subtitle'] ?? null,
            ':body'         => $data['body'] ?? null,
            ':url'          => $data['url'] ?? null,
            ':image_path'   => $data['image_path'] ?? null,
            ':sort_order'   => $data['sort_order'] ?? 0,
            ':is_active'    => $data['is_active'] ?? 1,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM cms_content WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    public function getAllJazzPerformers(): array
{
    $stmt = $this->connection->prepare("
        SELECT id, name
        FROM jazz_performers
        ORDER BY name ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}