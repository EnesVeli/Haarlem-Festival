<?php
$pageTitle = 'My Personal Program - The Festival Haarlem';
require __DIR__ . '/../partials/header.php';

$serviceFee = 2.50;
$vatRate    = 0.09;
$subtotal   = $cart['total'] ?? 0;
$vat        = $subtotal * $vatRate;
$total      = $subtotal + $serviceFee + $vat;
?>

<link href="/assets/css/stories.css" rel="stylesheet">
<link href="/assets/css/cart.css" rel="stylesheet">

<div class="stories-page">
    <div class="stories-container" style="padding: 2rem 0 3rem;">

        <h1 style="font-family:'Playfair Display',serif; font-size:2.4rem; color:#8b1e1e; margin:0 0 0.3rem;">
            My Personal Program
        </h1>
        <p style="color:#555; font-size:0.95rem; margin:0 0 1.5rem;">
            Your curated list of events for The Festival. Review your selections and proceed to payment.
        </p>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($cart['items'])): ?>
        <div style="text-align:center; padding:3rem 0;">
            <i class="bi bi-calendar-event" style="font-size:3rem; color:#ccc;"></i>
            <p style="margin:1rem 0 0.5rem; color:#888;">Your program is empty.</p>
            <a href="/tickets" class="stories-primary-button">Browse Events</a>
        </div>
        <?php else: ?>

        <div class="program-layout">
            <div class="program-items">
                <?php foreach ($cart['items'] as $item):
                $eventName  = $item['event_name'] ?? ('Event #' . $item['event_id']);
                $eventImage = $item['event_image'] ?? '/assets/images/stories/venue-placeholder.jpg';
                $venueName  = $item['venue_name'] ?? '';
                $eventStart = $item['event_start'] ?? null;
                $eventEnd   = $item['event_end'] ?? null;
                $dateLabel  = $eventStart ? date('D, M j', strtotime($eventStart)) : '';
                $timeLabel  = $eventStart ? (date('H:i', strtotime($eventStart)) . ' - ' . date('H:i', strtotime($eventEnd))) : '';
                ?>
                <div class="program-card">
                    <div class="program-card__image" style="background-image: url('<?= htmlspecialchars($eventImage) ?>');">
                        <?php if ($dateLabel): ?>
                        <div class="program-card__date-overlay">
                            <span class="program-card__date"><?= $dateLabel ?></span>
                            <span class="program-card__time"><?= $timeLabel ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="program-card__info">
                        <h3 class="program-card__name"><?= htmlspecialchars($eventName) ?></h3>
                        <?php if ($venueName): ?>
                        <p class="program-card__venue"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($venueName) ?></p>
                        <?php endif; ?>
                        <p class="program-card__ticket">
                            <?= (int)$item['quantity'] ?> &times; &euro;<?= number_format((float)$item['price'], 2) ?>
                        </p>
                        <p class="program-card__subtotal">&euro;<?= number_format((float)$item['subtotal'], 2) ?></p>
                    </div>

                    <form method="post" action="/cart/remove" class="program-card__remove">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                        <button type="submit" class="program-card__remove-btn" title="Remove">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="program-summary">
                <div class="program-summary__card">
                    <h3 class="program-summary__title">Summary</h3>

                    <div class="program-summary__row">
                        <span>Subtotal</span>
                        <span>&euro;<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="program-summary__row">
                        <span>Service Fees</span>
                        <span>&euro;<?= number_format($serviceFee, 2) ?></span>
                    </div>
                    <div class="program-summary__row">
                        <span>VAT (9%)</span>
                        <span>&euro;<?= number_format($vat, 2) ?></span>
                    </div>

                    <div class="program-summary__divider"></div>

                    <div class="program-summary__row program-summary__row--total">
                        <strong>Total</strong>
                        <strong style="color:#8b1e1e; font-size:1.3rem;">&euro;<?= number_format($total, 2) ?></strong>
                    </div>

                    <a href="/checkout" class="stories-booking-submit"
                        style="width:100%; text-align:center; margin-top:1rem; display:block;">
                        Proceed to Payment &rarr;
                    </a>
                </div>

                <a href="/tickets"
                    style="display:block; text-align:center; margin-top:1rem; color:#8b1e1e; font-size:0.9rem;">
                    &larr; Continue browsing events
                </a>
            </div>
        </div>

        <?php endif; ?>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
