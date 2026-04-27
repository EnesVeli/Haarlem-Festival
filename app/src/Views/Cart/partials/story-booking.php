<?
/** @var \App\Models\OrderItem $item */
?>

<div class="program-card">
    <div class="program-card__image" style="background-image: url('<?= $item->booking->event->image_path ?>');">
        <div class="program-card__date-overlay">
            <span class="program-card__date"><?= $item->booking->getBookingStartDate()->format('D, M j') ?></span>
            <span class="program-card__time"><?= $item->booking->getBookingStartDate()->format('H:i') . ' - ' . $item->booking->getBookingEndDate()->format('H:i') ?></span>
        </div>
    </div>

    <div class="program-card__info">
        <h3 class="program-card__name"><?= htmlspecialchars($item->booking->getEventName()) ?></h3>
        <p class="program-card__venue"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item->booking->event->address_name) ?></p>
        <p class="program-card__ticket">
            <?= "ticket number: " . $item->booking->quantity . "; language: " . $item->booking->event->language . ($item->booking->haarlem_pass ? '; haarlem pass' : '') ?>
        </p>
        <p class="program-card__subtotal">&euro;<?= number_format($item->price / 100, 2) ?></p>
    </div>

    <? require '/app/src/Views/cart/partials/remove-button.php'; ?>
</div>