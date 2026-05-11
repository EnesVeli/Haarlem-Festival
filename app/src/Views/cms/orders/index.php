<?
use App\Enums\OrderStatus;
/** @var ?App\ViewModels\CmsOrderListViewModel $view_model */
/** @var ?string $error_message */

$pageTitle = 'Order CMS - The Fsetical Haarlem';
$pageCSS = 'order.css';
?>

<?php require '/app/src/Views/partials/header.php'; ?>

<div>
    <? if(!isset($view_model)): ?>
        <div class="main-card">
            <h3 class="title">Order List:</h3>
            <div class="ohter-text">No</div>
        </div>
    <? else: ?>
        <div class="main-card">
            <h3 class="title">Order List:</h3>
            <div class="order-table-wrap">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="order-table-body">
                        <? foreach($view_model->orders as $order): ?>
                            <tr>
                                <th><?= $order->date->format('d.m.Y H:i:s') ?></th>
                                <th class="<?= $order->status == OrderStatus::Paid ? 'stauts-paid' : ($order->status == OrderStatus::Canceled ? 'status-cancelled' : '') ?>"><?= $order->getStatusString() ?></th>
                                <th><?= '€' . number_format($order->total_price / 100, 2) ?></th>
                                <th>
                                    <a class="view-button" href="<?= '/cms/order/view?id=' . $order->order_id ?>">View</a>
                                </th>
                            </tr>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div> 
            
            <?php include '/app/src/Views/cms/orders/partials/page-selector.php'; ?>      
        </div>
    <? endif; ?>
</div>

<?php require '/app/src/Views/partials/footer.php'; ?>