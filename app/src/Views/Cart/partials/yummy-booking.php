<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url('<?= '/assets/uploads/yummy/restaurants/' . $item->booking->restaurant->main_img_path ?>');">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->date_string ?></span>
            <span class="program-card__time"><?= $item->time_string ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name"><?= htmlspecialchars($item->booking->restaurant->name . " Reservation") ?></h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item->booking->restaurant->name) ?></p>
        <p class="program-card__ticket">
            <?= "adults: " . $item->booking->adult_number . "; children: " . $item->booking->child_number ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= $item->price_string ?></p>
    </div>

    <form method="post" action="/cart/remove" class="program-card__remove">
        <input type="hidden" name="order_item_id" value="<?= $item->item_id ?>">
        <button type="submit" class="program-card__remove-btn" title="Remove">
            <i class="bi bi-trash"></i> Remove
        </button>
    </form>
</div>

<?
//$eventName  = $item['event_name'] ?? ('Event #' . $item['event_id']);
//$eventImage = $item['event_image'] ?? '/assets/images/stories/venue-placeholder.jpg';
//$venueName  = $item['venue_name'] ?? '';
//$eventStart = $item['event_start'] ?? null;
//$eventEnd   = $item['event_end'] ?? null;
//$dateLabel  = $eventStart ? date('D, M j', strtotime($eventStart)) : '';
//$timeLabel  = $eventStart ? (date('H:i', strtotime($eventStart)) . ' - ' . date('H:i', strtotime($eventEnd))) : ''; 