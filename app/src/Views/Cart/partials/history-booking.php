<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url(/assets/uploads/history/bavo-church.jpg);">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->booking->getBookingStartDate()->format('D, M j') ?></span>
            <span class="program-card__time"><?= $item->booking->getBookingStartDate()->format('H:i') . ' - ' . $item->booking->getBookingEndDate()->format('H:i') ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name"><?= htmlspecialchars($item->booking->getEventName()) ?></h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i>St. Bavo Church</p>
        <p class="program-card__ticket">
            <?= "individual tickets: " . $item->booking->individual_count . "; family tickets: " . $item->booking->family_count . "; language: " . $item->booking->language ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= number_format($item->price / 100, 2) ?></p>
    </div>

    <? require '/app/src/Views/cart/partials/remove-button.php'; ?>
</div>