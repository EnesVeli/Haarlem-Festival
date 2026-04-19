<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url(/assets/uploads/history/bavo-church.jpg);">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->date_string ?></span>
            <span class="program-card__time"><?= $item->time_string ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name">Guided Tour Haarlem</h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i>St. Bavo Church</p>
        <p class="program-card__ticket">
            <?= "individual tickets: " . $item->booking->individual_count . "; family tickets: " . $item->booking->family_count . "; language: " . $item->booking->language ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= $item->price_string ?></p>
    </div>

    <? require '/app/src/Views/cart/partials/remove-button.php'; ?>
</div>

<?
//$eventName  = $item['event_name'] ?? ('Event #' . $item['event_id']);
//$eventImage = $item['event_image'] ?? '/assets/images/stories/venue-placeholder.jpg';
//$venueName  = $item['venue_name'] ?? '';
//$eventStart = $item['event_start'] ?? null;
//$eventEnd   = $item['event_end'] ?? null;
//$dateLabel  = $eventStart ? date('D, M j', strtotime($eventStart)) : '';
//$timeLabel  = $eventStart ? (date('H:i', strtotime($eventStart)) . ' - ' . date('H:i', strtotime($eventEnd))) : ''; 