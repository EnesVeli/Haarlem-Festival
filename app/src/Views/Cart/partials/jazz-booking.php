<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url(<?= $item->booking->performer->image_path?>);">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->date_string ?></span>
            <span class="program-card__time"><?= $item->time_string ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name">Tickets <?= $item->booking->performer->name ?></h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i><?= $item->booking->performer->venue_name ?></p>
        <p class="program-card__ticket">
            <?= "tickets: " . $item->booking->amount ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= $item->price_string ?></p>
    </div>

    <? require '/app/src/Views/cart/partials/remove-button.php'; ?>
</div>