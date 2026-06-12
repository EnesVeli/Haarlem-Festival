<?php
use App\Enums\BookingType;

$pageTitle = 'My Cart - The Festival Haarlem';

/** @var \App\ViewModels\Cart\CartViewModel $view_model */
/** @var ?string $error_message */
?>

<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/cart.css'; ?>
    <?php include '/app/public/assets/css/stories.css'; ?>
</style>

<div class="stories-page">
    <div class="stories-container" style="padding: 2rem 0 3rem;">

        <h1 style="font-family:'Playfair Display',serif; font-size:2.4rem; color:#8b1e1e; margin:0 0 0.3rem;">
            My Cart
        </h1>
        <p style="color:#555; font-size:0.95rem; margin:0 0 1.5rem;">
            Your curated list of events for The Festival. Review your selections and proceed to payment.
        </p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if (!isset($view_model) || count($view_model->order->order_items) <= 0): ?>
            <div style="text-align:center; padding:3rem 0;">
                <i class="bi bi-calendar-event" style="font-size:3rem; color:#ccc;"></i>
                <p style="margin:1rem 0 0.5rem; color:#888;">Your cart is empty.</p>
                <a href="/" class="stories-primary-button">Browse Events</a>
            </div>
        <?php else: ?>
            <div class="program-layout">
                <div class="program-items">
                    <? foreach ($view_model->order->order_items as $item): ?>
                        <div class="program-card">
                            <div class="program-card__image" style="background-image: url(<?= $item->booking->getEventImagePath() ?>);">
                                <div class="program-card__date-overlay">
                                    <span class="program-card__date"><?= $item->booking->getBookingStartDate()->format('D, M j') ?></span>
                                    <span class="program-card__time"><?= $item->booking->getBookingStartDate()->format('H:i') . ' - ' . $item->booking->getBookingEndDate()->format('H:i') ?></span>
                                </div>
                            </div>
                            <div class="program-card__info">
                                <h3 class="program-card__name"><?= htmlspecialchars($item->booking->getEventName()) ?></h3>
                                <p class="program-card__venue"><i class="bi bi-geo-alt"></i><?= $item->booking->getAddressShort() ?></p>
                                <p class="program-card__ticket"><?= $item->booking->getCartDescString() ?></p>
                                <p class="program-card__subtotal">&euro;<?= number_format($item->price / 100, 2) ?></p>
                            </div>
                            <form method="post" action="/cart/remove" class="program-card__remove">
                                <input type="hidden" name="item_id" value="<?= $item->item_id ?>">
                                <input type="hidden" name="order_id" value="<?= $item->order_id ?>">
                                <button type="submit" class="program-card__remove-btn" title="Remove">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </form>
                        </div>          
                    <? endforeach; ?>
                </div>

                <div class="program-summary">
                    <div class="program-summary__card">
                        <h3 class="program-summary__title">Summary</h3>

                        <? foreach($view_model->order->order_items as $item): ?>
                        <div class="program-summary__row">
                            <span><?= '- ' . $item->booking->getEventName(); ?></span>
                            <span>&euro;<?= number_format($item->price / 100, 2) ?></span>
                        </div>
                        <? endforeach; ?>

                        <div class="program-summary__divider"></div>

                        <div class="program-summary__row program-summary__row--total">
                            <strong>Total</strong>
                            <strong style="color:#8b1e1e; font-size:1.3rem;">&euro;<?= $view_model->total ?></strong>
                        </div>

                        <div class="program-summary__taxes_label">*all taxes included.</div>

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

<?php require '/app/src/Views/partials/footer.php';?>