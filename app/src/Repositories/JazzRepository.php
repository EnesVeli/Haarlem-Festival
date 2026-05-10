<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Interfaces\Repositories\IJazzRepository;
use App\Models\Jazz\JazzHero;
use App\Models\Jazz\JazzIntro;
use App\Models\Jazz\JazzExperience;
use App\Models\Jazz\JazzPerformer;
use App\Models\Jazz\JazzRecommendation;
use App\Models\Jazz\JazzLocation;
use App\Models\Jazz\JazzAppearance;
use App\Models\Jazz\JazzHighlight;
use App\Models\Jazz\JazzTrack;
use DateTime;
use PDO;

class JazzRepository extends Repository implements IJazzRepository
{
    private static ?JazzRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : JazzRepository {
        if(self::$_instance === null) self::$_instance = new JazzRepository();

        return self::$_instance;
    }

    public function getHero(): ?JazzHero
    {
        $stmt = $this->connection->prepare("SELECT * FROM jazz_hero WHERE is_active = 1 LIMIT 1");
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new JazzHero(
            (int) $row['id'],
            $row['title'] ?? '',
            $row['subtitle'] ?? '',
            $row['image_path'] ?? null,
            (int) $row['is_active']
        );
    }

    public function updateHero(array $data): void
    {
        $stmt = $this->connection->prepare("
            UPDATE jazz_hero
            SET title = :title,
                subtitle = :subtitle,
                is_active = :is_active,
                image_path = COALESCE(:image_path, image_path)
            WHERE id = :id
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':is_active' => $data['is_active'],
            ':image_path' => $data['image_path'] ?? null,
            ':id' => $data['id']
        ]);
    }

    // intro

    public function getIntro(): ?JazzIntro
    {
        $stmt = $this->connection->prepare("SELECT * FROM jazz_intro_content LIMIT 1");
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new JazzIntro(
            (int) $row['id'],
            $row['title'] ?? '',
            $row['description'] ?? ''
        );
    }

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

    // experiences

    public function getExperiences(): array
    {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM jazz_experiences 
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $experiences = [];

        foreach ($rows as $row) {
            $experiences[] = new JazzExperience(
                (int) $row['id'],
                $row['title'] ?? '',
                $row['description'] ?? '',
                $row['image_path'] ?? null,
                (int) $row['sort_order'],
                (int) $row['is_active']
            );
        }

        return $experiences;
    }

    public function getExperienceById(int $id): ?JazzExperience
    {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM jazz_experiences 
            WHERE id = :id 
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new JazzExperience(
            (int) $row['id'],
            $row['title'] ?? '',
            $row['description'] ?? '',
            $row['image_path'] ?? null,
            (int) $row['sort_order'],
            (int) $row['is_active']
        );
    }

    public function storeExperience(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_experiences (
                title,
                description,
                image_path,
                sort_order,
                is_active
            )
            VALUES (
                :title,
                :description,
                :image_path,
                :sort_order,
                :is_active
            )
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
        $stmt = $this->connection->prepare("
            UPDATE jazz_experiences
            SET title = :title,
                description = :description,
                sort_order = :sort_order,
                is_active = :is_active,
                image_path = COALESCE(:image_path, image_path)
            WHERE id = :id
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active'],
            ':image_path' => $data['image_path'] ?? null,
            ':id' => $data['id']
        ]);
    }

    public function deleteExperience(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM jazz_experiences WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }    
     // performers

    public function getAllPerformers(bool $onlyActive = true): array
    {
        $sql = "SELECT `id`, `name`, `price`, `bio`, `date` AS `date_`, `start_time` AS `start_time_`, `end_time` AS `end_time_`, `sort_order`, `is_active`,
            `image_path`, `performance_style`, `venue_name`, `venue_address`, `note_text`, `audio_url`, `hero_image_path` FROM jazz_performers";

        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }

        $sql .= " ORDER BY sort_order ASC, name ASC, id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, JazzPerformer::class);
        $res = $stmt->fetchAll();

        return $res === false ? null : $res;
    }

    public function getPerformerById(int $id): ?JazzPerformer
    {
        $stmt = $this->connection->prepare("SELECT `id`, `name`, `price`, `bio`, `date` AS `date_`, `start_time` AS `start_time_`, `end_time` AS `end_time_`, `sort_order`,
                `is_active`, `image_path`, `performance_style`, `venue_name`, `venue_address`, `note_text`, `audio_url`, `hero_image_path`
                FROM jazz_performers WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, JazzPerformer::class);
        $res = $stmt->fetch();

        return $res === false ? null : $res; 
    }

    public function storePerformer(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_performers (
                name,
                price,
                bio,
                performance_style,
                date,
                start_time,
                end_time,
                venue_name,
                venue_address,
                note_text,
                audio_url,
                image_path,
                hero_image_path,
                sort_order,
                is_active
            )
            VALUES (
                :name,
                :price,
                :bio,
                :performance_style,
                :date,
                :start_time,
                :end_time,
                :venue_name,
                :venue_address,
                :note_text,
                :audio_url,
                :image_path,
                :hero_image_path,
                :sort_order,
                :is_active
            )
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'] * 100,
            ':bio' => $data['bio'],
            ':performance_style' => $data['performance_style'],
            ':date' => new DateTime($data['date'])->format('Y-m-d H:i:s'),
            ':start_time' => new DateTime($data['start_time'])->format('Y-m-d H:i:s'),
            ':end_time' => new DateTime($data['end_time'])->format('Y-m-d H:i:s'),
            ':venue_name' => $data['venue_name'],
            ':venue_address' => $data['venue_address'],
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
        $stmt = $this->connection->prepare("
            UPDATE jazz_performers
            SET name = :name,
                price = :price,
                bio = :bio,
                performance_style = :performance_style,
                date = :date,
                start_time = :start_time,
                end_time = :end_time,
                venue_name = :venue_name,
                venue_address = :venue_address,
                note_text = :note_text,
                audio_url = :audio_url,
                sort_order = :sort_order,
                is_active = :is_active,
                image_path = COALESCE(:image_path, image_path),
                hero_image_path = COALESCE(:hero_image_path, hero_image_path)
            WHERE id = :id
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'] * 100,
            ':bio' => $data['bio'],
            ':performance_style' => $data['performance_style'],
            ':date' => new DateTime($data['date'])->format('Y-m-d H:i:s'),
            ':start_time' => new DateTime($data['start_time'])->format('Y-m-d H:i:s'),
            ':end_time' => new DateTime($data['end_time'])->format('Y-m-d H:i:s'),
            ':venue_name' => $data['venue_name'],
            ':venue_address' => $data['venue_address'],
            ':note_text' => $data['note_text'],
            ':audio_url' => $data['audio_url'],
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active'],
            ':image_path' => $data['image_path'] ?? null,
            ':hero_image_path' => $data['hero_image_path'] ?? null,
            ':id' => $data['id']
        ]);
    }

    public function deletePerformer(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM jazz_performers WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }    
     // performer extra data

     public function getAppearancesByPerformer(int $performerId): array
{
    $stmt = $this->connection->prepare("
        SELECT * 
        FROM jazz_performer_appearances 
        WHERE performer_id = :id
        ORDER BY sort_order ASC, id ASC
    ");
    $stmt->execute([':id' => $performerId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $appearances = [];

    foreach ($rows as $row) {
        $appearances[] = new JazzAppearance(
            (int) $row['id'],
            (int) $row['performer_id'],
            $row['day_text'] ?? '',
            $row['time_text'] ?? '',
            $row['location_text'] ?? null,
            $row['note_text'] ?? null,
            (int) $row['sort_order']
        );
    }

    return $appearances;
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

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $highlights = [];

    foreach ($rows as $row) {
        $highlights[] = new JazzHighlight(
            (int) $row['id'],
            (int) $row['performer_id'],
            $row['title'] ?? '',
            $row['description'] ?? '',
            (int) $row['sort_order']
        );
    }

    return $highlights;
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

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tracks = [];

    foreach ($rows as $row) {
        $tracks[] = new JazzTrack(
            (int) $row['id'],
            (int) $row['performer_id'],
            $row['title'] ?? '',
            $row['release_date_text'] ?? null,
            $row['description'] ?? null,
            $row['image_path'] ?? null,
            $row['listen_url'] ?? null,
            (int) $row['sort_order']
        );
    }

    return $tracks;
}
    // recommendations

    public function getRecommendations(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM jazz_recommendations ORDER BY id ASC");
        $stmt->execute();
    
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recommendations = [];
    
        foreach ($rows as $row) {
            $recommendations[] = new JazzRecommendation(
                (int) $row['id'],
                $row['title'] ?? '',
                $row['description'] ?? '',
                $row['url'] ?? null,
                $row['image_path'] ?? null,
                (int) $row['sort_order'],
                (int) $row['is_active']
            );
        }
    
        return $recommendations;
    }
    
    public function getRecommendationById(int $id): ?JazzRecommendation
    {
        $stmt = $this->connection->prepare("SELECT * FROM jazz_recommendations WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$row) {
            return null;
        }
    
        return new JazzRecommendation(
            (int) $row['id'],
            $row['title'] ?? '',
            $row['description'] ?? '',
            $row['url'] ?? null,
            $row['image_path'] ?? null,
            (int) $row['sort_order'],
            (int) $row['is_active']
        );
    }

    public function storeRecommendation(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_recommendations (
                title,
                description,
                url,
                image_path,
                sort_order,
                is_active
            )
            VALUES (
                :title,
                :description,
                :url,
                :image_path,
                :sort_order,
                :is_active
            )
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
        $stmt = $this->connection->prepare("
            UPDATE jazz_recommendations
            SET title = :title,
                description = :description,
                url = :url,
                sort_order = :sort_order,
                is_active = :is_active,
                image_path = COALESCE(:image_path, image_path)
            WHERE id = :id
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':url' => $data['url'],
            ':sort_order' => $data['sort_order'],
            ':is_active' => $data['is_active'],
            ':image_path' => $data['image_path'] ?? null,
            ':id' => $data['id']
        ]);
    }

    public function deleteRecommendation(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM jazz_recommendations WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }    
     // locations

    public function getLocations(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM jazz_locations ORDER BY id ASC");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $locations = [];

        foreach ($rows as $row) {
            $locations[] = new JazzLocation(
                (int) $row['id'],
                $row['name'] ?? '',
                $row['address'] ?? '',
                $row['google_maps_embed_url'] ?? null,
                (int) $row['is_active']
            );
        }

        return $locations;
    }

    public function getLocationById(int $id): ?JazzLocation
    {
        $stmt = $this->connection->prepare("SELECT * FROM jazz_locations WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new JazzLocation(
            (int) $row['id'],
            $row['name'] ?? '',
            $row['address'] ?? '',
            $row['google_maps_embed_url'] ?? null,
            (int) $row['is_active']
        );
    }

    public function getLocationsByPerformer(int $performerId): array
    {
        $stmt = $this->connection->prepare("
            SELECT l.*
            FROM jazz_locations l
            JOIN jazz_performer_locations pl ON l.id = pl.location_id
            WHERE pl.performer_id = :id
            ORDER BY pl.sort_order ASC, l.id ASC
        ");
    
        $stmt->execute([':id' => $performerId]);
    
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $locations = [];
    
        foreach ($rows as $row) {
            $locations[] = new JazzLocation(
                (int) $row['id'],
                $row['name'] ?? '',
                $row['address'] ?? '',
                $row['google_maps_embed_url'] ?? null,
                (int) $row['is_active']
            );
        }
    
        return $locations;
    }

    public function storeLocation(array $data): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO jazz_locations (
                name,
                address,
                google_maps_embed_url,
                is_active
            )
            VALUES (
                :name,
                :address,
                :google_maps_embed_url,
                :is_active
            )
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
        $stmt = $this->connection->prepare("DELETE FROM jazz_locations WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    public function updateHighlightsByPerformer(int $performerId, array $highlights): void
    {
        $deleteStmt = $this->connection->prepare("
            DELETE FROM jazz_performer_highlights
            WHERE performer_id = :performer_id
        ");
        $deleteStmt->execute([':performer_id' => $performerId]);

        $insertStmt = $this->connection->prepare("
            INSERT INTO jazz_performer_highlights (
                performer_id,
                title,
                description,
                sort_order
            )
            VALUES (
                :performer_id,
                :title,
                :description,
                :sort_order
            )
        ");

        foreach ($highlights as $highlight) {
            $title = trim($highlight['title'] ?? '');

            if ($title === '') {
                continue;
            }

            $insertStmt->execute([
                ':performer_id' => $performerId,
                ':title' => $title,
                ':description' => $highlight['description'] ?? '',
                ':sort_order' => (int)($highlight['sort_order'] ?? 0),
            ]);
        }
    }

    public function updateTracksByPerformer(int $performerId, array $tracks): void
    {
        $deleteStmt = $this->connection->prepare("
            DELETE FROM jazz_performer_tracks
            WHERE performer_id = :performer_id
        ");
        $deleteStmt->execute([':performer_id' => $performerId]);

        $insertStmt = $this->connection->prepare("
            INSERT INTO jazz_performer_tracks (
                performer_id,
                title,
                release_date_text,
                description,
                image_path,
                listen_url,
                sort_order
            )
            VALUES (
                :performer_id,
                :title,
                :release_date_text,
                :description,
                :image_path,
                :listen_url,
                :sort_order
            )
        ");

        foreach ($tracks as $track) {
            $title = trim($track['title'] ?? '');

            if ($title === '') {
                continue;
            }

            $insertStmt->execute([
                ':performer_id' => $performerId,
                ':title' => $title,
                ':release_date_text' => $track['release_date_text'] ?? null,
                ':description' => $track['description'] ?? null,
                ':image_path' => $track['image_path'] ?? null,
                ':listen_url' => $track['listen_url'] ?? null,
                ':sort_order' => (int)($track['sort_order'] ?? 0),
            ]);
        }
    }

    /**
     * Returns list of jazz performers for ticket page (only a few performer fields are set) limited by page.
     * @param int $page page to search (1 is first page. should be 1 or higher)
     * @param int $perf_per_page number of restaurant per page (should be more or equal to 1)
     * @return JazzPerformer[]|null|bool returns false if any errors occured. returns null if no restaurants found. returns array of restaurants otherwise.
     */
    public function getActivePerformersForTickets(int $page, int $perf_per_page) : array|null|bool {
        $sql = "SELECT `id`, `name`, `bio`, `date` AS `date_`, `start_time` AS `start_time_`, `end_time` AS `end_time_`, `image_path`
                FROM `jazz_performers` 
                WHERE `is_active` = 1
                LIMIT :limit OFFSET :offset;";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('limit', $perf_per_page, PDO::PARAM_INT);
        $stmt->bindValue('offset', $perf_per_page * ($page - 1), PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, JazzPerformer::class);

        return $stmt->fetchAll();
    }

    /**
     * Returns total number of active performers.
     * @return int|bool returns false if there were errors during execution. Returns number of restaurants on success. 
     */
    public function getNumberOfActivePerformers() : int|bool {
        $sql = "SELECT COUNT(*) 
                FROM `jazz_performers`
                WHERE `is_active` = 1;";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_BOTH);

        return $res === false ? false : $res[0];
    }
}