<?
/** @var ?App\ViewModels\CmsOrderListViewModel $view_model */
/** @var ?string $error_message */

$pageTitle = 'Order CMS - The Fsetical Haarlem';
$pageCSS = 'order.css';
?>

<?php require '/app/src/Views/partials/header.php'; ?>

<div>
    <? if(!isset($view_model)): ?>
        <div>
            <h3>No Orders found.</h3>
            <div></div>
        </div>
    <? else: ?>
        <div>
            <table>
                <thead>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <? foreach($view_model->orders as $order): ?>
                        <tr>
                            <th><?= $order->date->format('d.m.Y H:i:s') ?></th>
                            <th><?= $order->status->value ?></th>
                            <th><?= '€' . number_format($order->total_price / 100, 2) ?></th>
                            <th></th>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>
</div>

<?php require '/app/src/Views/partials/footer.php'; ?>