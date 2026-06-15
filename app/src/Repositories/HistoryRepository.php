<?php

namespace App\Repositories;

use App\Models\History\HistoryReservationSlot;
use App\Models\History\HistoryTimeSlot;
use PDO;
use App\Framework\Repository;

class HistoryRepository extends Repository
{
    private static ?HistoryRepository $_instance = null;
    private const HIGHLIGHT_COLUMNS = "`id`, `title`, `description`, `image`";
    private const TICKET_COLUMNS = "`id`, `title`, `time_slot`, `available_spots`, `price`";
    private const TICKET_PRICE_COLUMNS = "`id`, `ticket_type`, `price`";
    private const CONTENT_COLUMNS = "`id`, `section`, `title`, `subtitle`, `image`, `image_left`, `image_right`";
    private const DETAIL_COLUMNS = "`id`, `highlight_id`, `slug`, `page_title`, `hero_image`, `location`, `founded_year`, `style_type`, `meta_description`";
    private const SECTION_COLUMNS = "`id`, `detail_id`, `section_type`, `section_title`, `content`, `image_path`, `sort_order`";
    private const GALLERY_COLUMNS = "`id`, `detail_id`, `image_path`, `caption`, `sort_order`";
    private const FACT_COLUMNS = "`id`, `detail_id`, `icon`, `label`, `value`, `sort_order`";

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : HistoryRepository {
        if(self::$_instance === null) self::$_instance = new HistoryRepository();

        return self::$_instance;
    }

    public function getAllHighlights()
    {
        $sql = "SELECT " . self::HIGHLIGHT_COLUMNS . " FROM history_highlights ORDER BY id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableTickets()
    {
        $sql = "SELECT " . self::TICKET_COLUMNS . "
                FROM history_tickets
                WHERE available_spots > 0
                ORDER BY time_slot ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketPrices(): array
    {
        $sql  = "SELECT " . self::TICKET_PRICE_COLUMNS . " FROM history_ticket_prices";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $result[$row['ticket_type']] = $row;
        }
        return $result;
    }

    public function updateTicketPrice(string $type, float $price): void
    {
        $sql  = "UPDATE history_ticket_prices SET price = :price WHERE ticket_type = :type";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':price' => $price, ':type' => $type]);
    }

    public function getContentBySection($section)
    {
        $sql = "SELECT " . self::CONTENT_COLUMNS . " FROM history_content WHERE section = :section LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContent()
    {
        $sql = "SELECT " . self::CONTENT_COLUMNS . " FROM history_content ORDER BY id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailBySlug($slug)
    {
        $sql = "SELECT hd.`id`, hd.`highlight_id`, hd.`slug`, hd.`page_title`, hd.`hero_image`,
                       hd.`location`, hd.`founded_year`, hd.`style_type`, hd.`meta_description`,
                       hh.`title` AS `highlight_title`, hh.`description` AS `highlight_description`
                FROM history_details hd
                LEFT JOIN history_highlights hh ON hd.highlight_id = hh.id
                WHERE hd.slug = :slug
                LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailSections($detailId)
    {
        $sql = "SELECT " . self::SECTION_COLUMNS . " FROM history_detail_sections 
                WHERE detail_id = :detail_id 
                ORDER BY sort_order ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':detail_id', $detailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailGallery($detailId)
    {
        $sql = "SELECT " . self::GALLERY_COLUMNS . " FROM history_detail_gallery 
                WHERE detail_id = :detail_id 
                ORDER BY sort_order ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':detail_id', $detailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailFacts($detailId)
    {
        $sql = "SELECT " . self::FACT_COLUMNS . " FROM history_detail_facts 
                WHERE detail_id = :detail_id 
                ORDER BY sort_order ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':detail_id', $detailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHighlightWithSlug($highlightId)
    {
        $sql = "SELECT hh.`id`, hh.`title`, hh.`description`, hh.`image`, hd.`slug`
                FROM history_highlights hh
                LEFT JOIN history_details hd ON hh.id = hd.highlight_id
                WHERE hh.id = :id
                LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $highlightId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllHighlightsWithSlugs()
    {
        $sql = "SELECT hh.`id`, hh.`title`, hh.`description`, hh.`image`, hd.`slug`
                FROM history_highlights hh
                LEFT JOIN history_details hd ON hh.id = hd.highlight_id
                ORDER BY hh.id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoryReservationSlotById(int $reservation_id) : ?HistoryReservationSlot{
        $stmt = $this->connection->prepare("SELECT `reservation_id`, `slot_id`, `date` AS `date_`, `booked` FROM `HistoryReservationSlots` WHERE `reservation_id` = :reservation_id;");

        $stmt->bindParam('reservation_id', $reservation_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, HistoryReservationSlot::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

    public function getHistoryTimeSlotById(int $slot_id) : ?HistoryTimeSlot{
        $stmt = $this->connection->prepare("SELECT `slot_id`, `time` AS `time_`, `capacity` FROM `HistoryTimeSlot` WHERE `slot_id` = :slot_id;");

        $stmt->bindParam('slot_id', $slot_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, HistoryTimeSlot::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

    public function getAllTimeSlots() : ?array{
        $stmt = $this->connection->prepare("SELECT `slot_id`, `time` AS `time_`, `capacity` FROM `HistoryTimeSlot`;");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, HistoryTimeSlot::class);
        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }

    public function getHistoryReservationBySlotIdAndDateOffset(int $slot_id, int $date_offset) : ?HistoryReservationSlot {  
        $stmt = $this->connection->prepare("SELECT `reservation_id`, `slot_id`, `date` AS `date_`, `booked` FROM `HistoryReservationSlots`
                WHERE `slot_id` = :slot_id AND `date` = DATE(NOW()) + INTERVAL +:date_offset DAY;");

        $stmt->bindParam('slot_id', $slot_id, PDO::PARAM_INT);
        $stmt->bindParam('date_offset', $date_offset, PDO::PARAM_INT);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, HistoryReservationSlot::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

    public function addHistoryReservationBySlotIdAndDateOffset(int $slot_id, int $date_offset) : ?int {
        $stmt = $this->connection->prepare("INSERT INTO `HistoryReservationSlots`(`slot_id`, `date`, `booked`) 
                VALUES (:slot_id, DATE(NOW()) + INTERVAL +:date_offset DAY, 0);");

        $stmt->bindParam('slot_id', $slot_id, PDO::PARAM_INT);
        $stmt->bindParam('date_offset', $date_offset, PDO::PARAM_INT);

        $res = $stmt->execute();

        if(!$res) return null;

        return $this->connection->lastInsertId();
    }
}
