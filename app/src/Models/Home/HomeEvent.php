<?php

namespace App\Models;

/**
 * Represents a row from the `home_events` table (an event card on the homepage).
 */
class HomeEvent
{
    public ?int $id;
    public string $title;
    public string $category;
    public ?string $shortDescription;
    public ?string $longDescription;
    public ?string $venues;
    public ?string $url;
    public ?string $buttonLabel;
    public ?string $icon;
    public ?string $bgClass;
    public ?string $image;
    public int $sortOrder;
    public bool $isActive;

    public function __construct(
        ?int $id,
        string $title,
        string $category,
        ?string $shortDescription,
        ?string $longDescription,
        ?string $venues,
        ?string $url,
        ?string $buttonLabel,
        ?string $icon,
        ?string $bgClass,
        ?string $image,
        int $sortOrder,
        bool $isActive
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->shortDescription = $shortDescription;
        $this->longDescription = $longDescription;
        $this->venues = $venues;
        $this->url = $url;
        $this->buttonLabel = $buttonLabel;
        $this->icon = $icon;
        $this->bgClass = $bgClass;
        $this->image = $image;
        $this->sortOrder = $sortOrder;
        $this->isActive = $isActive;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            isset($row['id']) ? (int)$row['id'] : null,
            (string)$row['title'],
            (string)$row['category'],
            $row['short_description'] ?? null,
            $row['long_description'] ?? null,
            $row['venues'] ?? null,
            $row['url'] ?? null,
            $row['button_label'] ?? null,
            $row['icon'] ?? null,
            $row['bg_class'] ?? null,
            $row['image'] ?? null,
            (int)($row['sort_order'] ?? 0),
            (bool)($row['is_active'] ?? false)
        );
    }

    /**
     * @return array<string, mixed> Associative array suitable for binding to a prepared statement.
     */
    public function toParams(): array
    {
        return [
            ':title'             => $this->title,
            ':category'          => $this->category,
            ':short_description' => $this->shortDescription,
            ':long_description'  => $this->longDescription,
            ':venues'            => $this->venues,
            ':url'               => $this->url,
            ':button_label'      => $this->buttonLabel,
            ':icon'              => $this->icon,
            ':bg_class'          => $this->bgClass,
            ':image'             => $this->image,
            ':sort_order'        => $this->sortOrder,
            ':is_active'         => $this->isActive ? 1 : 0,
        ];
    }
}
