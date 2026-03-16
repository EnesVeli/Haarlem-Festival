<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class JazzRepository extends Repository
{
    /*
    |--------------------------------------------------------------------------
    | Public / Shared Read Methods
    |--------------------------------------------------------------------------
    */

    public function getHero(): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_hero
            WHERE is_active = 1
            LIMIT 1
        ");

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getIntro(): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_intro_content
            LIMIT 1
        ");

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getExperiences(): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_experiences
            ORDER BY sort_order ASC, id ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExperienceById(int $id): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_experiences
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getRecommendations(): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_recommendations
            ORDER BY sort_order ASC, id ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecommendationById(int $id): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_recommendations
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getAllPerformers(bool $onlyActive = true): array
    {
        $sql = "
            SELECT *
            FROM jazz_performers
        ";

        if ($onlyActive) {
            $sql .= " WHERE is_active = 1 ";
        }

        $sql .= " ORDER BY sort_order ASC, name ASC, id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerformerById(int $id): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_performers
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getAppearancesByPerformer(int $performerId): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_performer_appearances
            WHERE performer_id = :id
            ORDER BY sort_order ASC, id ASC
        ");

        $stmt->execute([':id' => $performerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHighlightsByPerformer(int $performerId): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_performer_highlights
            WHERE performer_id = :id
            ORDER BY sort_order ASC, id ASC
        ");

        $stmt->execute([':id' => $performerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTracksByPerformer(int $performerId): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_performer_tracks
            WHERE performer_id = :id
            ORDER BY sort_order ASC, id ASC
        ");

        $stmt->execute([':id' => $performerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocations(): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_locations
            ORDER BY id ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocationById(int $id): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM jazz_locations
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getLocationsByPerformer(int $performerId): array
    {
        $stmt = $this->connection->prepare("
            SELECT l.*
            FROM jazz_locations l
            JOIN jazz_performer_locations pl
                ON l.id = pl.location_id
            WHERE pl.performer_id = :id
            ORDER BY pl.sort_order ASC, l.id ASC
        ");

        $stmt->execute([':id' => $performerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    */

    public function updateHero(array $data): void
    {
        $sql = "
            UPDATE jazz_hero
            SET title = :title,
                subtitle = :subtitle,
                is_active = :is_active
                " . (!empty($data['image_path']) ? ", image_path = :image_path" : "") . "
            WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);

        $params = [
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':is_active' => $data['is_active'],
            ':id' => $data['id']
        ];

        if (!empty($data['image_path'])) {
            $params[':image_path'] = $data['image_path'];
        }

        $stmt->execute($params);
    }

    /*
    |--------------------------------------------------------------------------
    | Intro
    |--------------------------------------------------------------------------
    */

    public function updateIntro(array $data): void
    {
        $stmt = $this->connection->prepare("
            UPDATE jazz_intro_content
            SET title = :title,
                description = :description
            WHERE id = :id
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':id' => $data['id']
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Experiences
    |--------------------------------------------------------------------------
    */

    public function storeExperience(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_experiences (title, description, image_path, sort_order, is_active)
            VALUES (:title, :description, :image_path, :sort_order, :is_active)
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image_path' => $data['image_path'] ?? null,
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active']
        ]);
    }

    public function updateExperience(array $data): void
    {
        $sql = "
            UPDATE jazz_experiences
            SET title = :title,
                description = :description,
                sort_order = :sort_order,
                is_active = :is_active
                " . (!empty($data['image_path']) ? ", image_path = :image_path" : "") . "
            WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);

        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active'],
            ':id' => $data['id']
        ];

        if (!empty($data['image_path'])) {
            $params[':image_path'] = $data['image_path'];
        }

        $stmt->execute($params);
    }

    public function deleteExperience(int $id): void
    {
        $stmt = $this->connection->prepare("
            DELETE FROM jazz_experiences
            WHERE id = :id
        ");

        $stmt->execute([':id' => $id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Performers
    |--------------------------------------------------------------------------
    */

    public function storePerformer(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_performers (
                name, bio, performance_style, event_date_text, event_time_text,
                venue_name, venue_address, price_text, note_text, audio_url,
                image_path, hero_image_path, sort_order, is_active
            )
            VALUES (
                :name, :bio, :performance_style, :event_date_text, :event_time_text,
                :venue_name, :venue_address, :price_text, :note_text, :audio_url,
                :image_path, :hero_image_path, :sort_order, :is_active
            )
        ");
    
        $stmt->execute([
            ':name' => $data['name'],
            ':bio' => $data['bio'],
            ':performance_style' => $data['performance_style'],
            ':event_date_text' => $data['event_date_text'],
            ':event_time_text' => $data['event_time_text'],
            ':venue_name' => $data['venue_name'],
            ':venue_address' => $data['venue_address'],
            ':price_text' => $data['price_text'],
            ':note_text' => $data['note_text'],
            ':audio_url' => $data['audio_url'],
            ':image_path' => $data['image_path'] ?? null,
            ':hero_image_path' => $data['hero_image_path'] ?? null,
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active']
        ]);
    }
    
    public function updatePerformer(array $data): void
    {
        $sql = "
            UPDATE jazz_performers
            SET name = :name,
                bio = :bio,
                performance_style = :performance_style,
                event_date_text = :event_date_text,
                event_time_text = :event_time_text,
                venue_name = :venue_name,
                venue_address = :venue_address,
                price_text = :price_text,
                note_text = :note_text,
                audio_url = :audio_url,
                sort_order = :sort_order,
                is_active = :is_active
                " . (!empty($data['image_path']) ? ", image_path = :image_path" : "") . "
                " . (!empty($data['hero_image_path']) ? ", hero_image_path = :hero_image_path" : "") . "
            WHERE id = :id
        ";
    
        $stmt = $this->connection->prepare($sql);
    
        $params = [
            ':name' => $data['name'],
            ':bio' => $data['bio'],
            ':performance_style' => $data['performance_style'],
            ':event_date_text' => $data['event_date_text'],
            ':event_time_text' => $data['event_time_text'],
            ':venue_name' => $data['venue_name'],
            ':venue_address' => $data['venue_address'],
            ':price_text' => $data['price_text'],
            ':note_text' => $data['note_text'],
            ':audio_url' => $data['audio_url'],
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active'],
            ':id' => $data['id']
        ];
    
        if (!empty($data['image_path'])) {
            $params[':image_path'] = $data['image_path'];
        }
    
        if (!empty($data['hero_image_path'])) {
            $params[':hero_image_path'] = $data['hero_image_path'];
        }
    
        $stmt->execute($params);
    }    public function deletePerformer(int $id): void
    {
        $stmt = $this->connection->prepare("
            DELETE FROM jazz_performers
            WHERE id = :id
        ");

        $stmt->execute([':id' => $id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Recommendations
    |--------------------------------------------------------------------------
    */

    public function storeRecommendation(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_recommendations (title, description, url, image_path, sort_order, is_active)
            VALUES (:title, :description, :url, :image_path, :sort_order, :is_active)
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':url' => $data['url'],
            ':image_path' => $data['image_path'] ?? null,
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active']
        ]);
    }

    public function updateRecommendation(array $data): void
    {
        $sql = "
            UPDATE jazz_recommendations
            SET title = :title,
                description = :description,
                url = :url,
                sort_order = :sort_order,
                is_active = :is_active
                " . (!empty($data['image_path']) ? ", image_path = :image_path" : "") . "
            WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);

        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':url' => $data['url'],
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active'],
            ':id' => $data['id']
        ];

        if (!empty($data['image_path'])) {
            $params[':image_path'] = $data['image_path'];
        }

        $stmt->execute($params);
    }

    public function deleteRecommendation(int $id): void
    {
        $stmt = $this->connection->prepare("
            DELETE FROM jazz_recommendations
            WHERE id = :id
        ");

        $stmt->execute([':id' => $id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Locations
    |--------------------------------------------------------------------------
    */

    public function storeLocation(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_locations (name, address, google_maps_embed_url, is_active)
            VALUES (:name, :address, :google_maps_embed_url, :is_active)
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'],
            ':google_maps_embed_url' => $data['google_maps_embed_url'],
            ':is_active' => $data['is_active']
        ]);
    }

    public function updateLocation(array $data): void
    {
        $stmt = $this->connection->prepare("
            UPDATE jazz_locations
            SET name = :name,
                address = :address,
                google_maps_embed_url = :google_maps_embed_url,
                is_active = :is_active
            WHERE id = :id
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'],
            ':google_maps_embed_url' => $data['google_maps_embed_url'],
            ':is_active' => $data['is_active'],
            ':id' => $data['id']
        ]);
    }

    public function deleteLocation(int $id): void
    {
        $stmt = $this->connection->prepare("
            DELETE FROM jazz_locations
            WHERE id = :id
        ");

        $stmt->execute([':id' => $id]);
    }
}