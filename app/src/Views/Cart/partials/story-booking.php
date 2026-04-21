<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url('<?= $item->booking->event->image_path ?>');">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->date_string ?></span>
            <span class="program-card__time"><?= $item->time_string ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name"><?= htmlspecialchars($item->booking->event->name . " Reservation") ?></h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item->booking->event->address_name) ?></p>
        <p class="program-card__ticket">
            <?= "ticket number: " . $item->booking->quantity . "; language: " . $item->booking->event->language . ($item->booking->haarlem_pass ? '; haarlem pass' : '') ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= $item->price_string ?></p>
    </div>

    <? require '/app/src/Views/cart/partials/remove-button.php'; ?>
</div>