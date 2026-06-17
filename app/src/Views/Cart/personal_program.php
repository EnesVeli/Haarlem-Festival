<?php
use App\Enums\OrderStatus;

$pageTitle = 'My Personal Program - The Festival Haarlem';

/** @var ?\App\ViewModels\Cart\PersonalProgramViewModel $view_model */
/** @var ?string $error_message */
?>

<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/cart.css'; ?>
    <?php include '/app/public/assets/css/stories.css'; ?>
</style>

<div class="stories-page">
    <div class="stories-container" style="padding: 2rem 0 3rem;">
        <?php if (!isset($view_model) || !isset($view_model->orders) || count($view_model->orders) <= 0): ?>
            <div style="text-align:center; padding:3rem 0; margin-bottom: 80px;">
                <i class="bi bi-calendar-event" style="font-size:3rem; color:#ccc;"></i>
                <p style="margin:1rem 0 0.5rem; color:#888;">Your personal program is empty.</p>
                <a href="/" class="stories-primary-button">Browse Events</a>
            </div>
        <?php else: ?>
            <div class="prog-layout">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>

                <?php if(!empty($success_message)): ?>
                    <div class="alert alert-success" role="alert"><?= htmlspecialchars($success_message) ?></div>
                <?php endif; ?>

                <h1 style="font-family:'Playfair Display',serif; font-size:2.4rem; color:#8b1e1e; margin:0 0 0.3rem;">
                    My Personal Program
                </h1>
                <p style="color:#555; font-size:0.95rem; margin:0 0 1.5rem;">
                    Your orders list with your events. View your selections.
                </p>
                <div class="program-items">
                    <? foreach ($view_model->orders as $order): ?>
                        <div class="prog-order">
                            <div class="prog-order-label">Order #<?= 132482 - ($order->order_id + $order->order_id * 117) ?></div>    
                            <div class="prog-prder-break"></div>                
                            <div class="prog-order-price-container">
                                <div class="prog-order-price-label">Total Price:</div>
                                <div class="prog-order-price">&euro;<?= number_format($order->total_price / 100, 2) ?></div>
                            </div>
                            <div class="prog-order-price-container">
                                <div class="prog-order-price-label">Order Date:</div>
                                <div class="prog-order-date"><?= $order->date != null ? $order->date->format('d.m.Y H:i:s') : '-' ?></div>
                            </div>
                            <div class="prog-order-price-container">
                                <div class="prog-order-price-label">Status:</div>
                                <div class="<?= ($order->status == OrderStatus::Paid ? 'prog-order-status-paid' : 'prog-order-status-not-paid') ?>"><?= $order->status == OrderStatus::Paid ? 'Paid' : 'Pending' ?></div>
                            </div>
                            
                            <div>                         
                                <? if($order->status == OrderStatus::NotPaid): ?>
                                    <div class="prog-order-price-label">Actions:</div>   
                                    <div class="prog-order-actions">
                                        <div>
                                            <form method="post" action="/payment/notpaid/cancel">
                                                <input type="hidden" name="order_id" value="<?= $order->order_id ?>">
                                                <button type="submit" class="prog-order-cancel-button">Cancel</button>
                                            </form>
                                        </div>
                                        <div>
                                            <form method="post" action="/payment/notpaid/pay">
                                                <input type="hidden" name="order_id" value="<?= $order->order_id ?>">
                                                <button type="submit" class="prog-order-pay-button">Pay</button>
                                            </form>
                                        </div>
                                    </div> 
                                <? endif; ?>                      
                            </div>
                            <div class="prog-prder-break"></div>   
                            <div class="prog-order-price-label">Items:</div>

                            <div class="prog-order-items">
                                <? foreach($order->order_items as $item): ?>
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
                                    </div>  
                                <? endforeach; ?>
                            </div>                                                              
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>