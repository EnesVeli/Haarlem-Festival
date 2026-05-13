<?
use App\Enums\OrderStatus;

/** @var ?App\ViewModels\ViewOrderCmsViewModel $view_model */
/** @var ?string $error_message */

$pageTitle = 'View Order CMS - The Festival Haarlem';
$pageCSS = 'order.css';
?>

<?php require '/app/src/Views/partials/header.php'; ?>

<div class="view-order-main">
    <h3 class="view-title">Order:</h3>

    <div class="order-view-stat-container">
        <h3>Status:</h3>
        <div class="<?= $view_model->order->status == OrderStatus::Paid ? 'stauts-paid' : ($view_model->order->status == OrderStatus::Canceled ? 'status-cancelled' : '') ?>"><?= $view_model->order->getStatusString() ?></div>
    </div>
    <div class="order-view-stat-container">
        <h3>Price:</h3>
        <div><?= '€' . number_format($view_model->order->total_price / 100, 2) ?></div>
    </div>
    <div class="order-view-stat-container">
        <h3>Date:</h3>
        <div><?= $view_model->order->date->format('d.m.Y H:i:s') ?></div>
    </div>

    <? if(isset($view_model->order->order_items)): ?>
        <div class="order-items-container">
            <h3>Items:</h3>
            
            <div class="view-order-table-wrap">
                <table class="view-order-table">
                    <thead>
                        <tr>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody class="view-order-table-body">
                        <? foreach($view_model->order->order_items as $item): ?>
                            <tr>
                                <th><?= '€' . number_format($item->price / 100, 2) ?></th>
                                <th><?= $item->getBookingTypeString() ?></th>
                                <th><?= $item->booking->getEventName() ?></th>
                            </tr>
                        <? endforeach; ?>
                    </tbody>
                </table>  
            </div>        
            
            <div class="view-order-action-container">
                <a class="order-view-go-back-button" href="/cms/order">Go Back</a>
            </div>
        </div>
    <? else: ?>
        <div class="order-items-container">
            <h3>No items found.</h3>

            <div class="view-order-action-container">
                <a class="order-view-go-back-button" href="/cms/order">Go Back</a>
            </div>
        </div>
    <? endif; ?>
</div>

<?php require '/app/src/Views/partials/footer.php'; ?>