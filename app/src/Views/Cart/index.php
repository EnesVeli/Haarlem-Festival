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

        <?php if ($view_model == null): ?>
            <div style="text-align:center; padding:3rem 0;">
                <i class="bi bi-calendar-event" style="font-size:3rem; color:#ccc;"></i>
                <p style="margin:1rem 0 0.5rem; color:#888;">Your cart is empty.</p>
                <a href="/tickets" class="stories-primary-button">Browse Events</a>
            </div>
        <?php else: ?>
            <div class="program-layout">
                <div class="program-items">
                    <? foreach ($view_model->order->order_items as $item): ?>
                        <? switch($item->booking_type){
                            case BookingType::Yummy:
                                require '/app/src/Views/cart/partials/yummy-booking.php';
                                break;
                           }
                        ?>               
                    <? endforeach; ?>
                </div>

                <div class="program-summary">
                    <div class="program-summary__card">
                        <h3 class="program-summary__title">Summary</h3>

                        <div class="program-summary__row">
                            <span>Subtotal</span>
                            <span>&euro;<?= $view_model->sub_total ?></span>
                        </div>
                        <div class="program-summary__row">
                            <span>VAT (<?= $view_model->vat_persent ?>%)</span>
                            <span>&euro;<?= $view_model->vat_cost ?></span>
                        </div>

                        <div class="program-summary__divider"></div>

                        <div class="program-summary__row program-summary__row--total">
                            <strong>Total</strong>
                            <strong style="color:#8b1e1e; font-size:1.3rem;">&euro;<?= $view_model->total ?></strong>
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

<?php require '/app/src/Views/partials/footer.php';?>