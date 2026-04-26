<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url('<?= '/assets/uploads/yummy/restaurants/' . $item->booking->restaurant->main_img_path ?>');">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->booking->getBookingStartDate()->format('D, M j') ?></span>
            <span class="program-card__time"><?= $item->booking->getBookingStartDate()->format('H:i') . ' - ' . $item->booking->getBookingEndDate()->format('H:i') ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name"><?= htmlspecialchars($item->booking->getEventName()) ?></h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item->booking->restaurant->name) ?></p>
        <p class="program-card__ticket">
            <?= "adults: " . $item->booking->adult_number . "; children: " . $item->booking->child_number ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= number_format($item->price / 100, 2) ?></p>
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