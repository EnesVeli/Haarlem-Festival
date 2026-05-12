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
                            <th>
                                <span id="sort_0" class="" onclick="onSortingClick(0)">
                                    Date
                                    <span id="ord_0_0" style="display:none;">&#8593;</span>
                                    <span id="ord_0_1" style="display:none;">&#8595;</span>
                                </span>
                            </th>
                            <th>
                                <span id="sort_1" class="" onclick="onSortingClick(1)">
                                    Status
                                    <span id="ord_1_0" style="display:none;">&#8593;</span>
                                    <span id="ord_1_1" style="display:none;">&#8595;</span>
                                </span>
                            </th>
                            <th>
                                <span id="sort_2" class="" onclick="onSortingClick(2)">
                                    Price
                                    <span id="ord_2_0" style="display:none;">&#8593;</span>
                                    <span id="ord_2_1" style="display:none;">&#8595;</span>
                                </span>
                            </th>
                            <th>
                                <span style="cursor: auto;">
                                    Action
                                </span>
                            </th>
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

<script>
    let sort = <?= $view_model == null ? 0 : $view_model->sorting ?>;
    let order = <?= $view_model == null ? 0 : $view_model->sorting_order ?>;

    updateSorting(sort, order);

    function updateSorting(new_sort, new_order){
        hideSingle('sort_' + sort, 'ord_' + sort + '_' + order);
        showSingle('sort_' + new_sort, 'ord_' + new_sort + '_' + new_order);
    }

    function hideSingle(sort_id, arrow_id){
        title = document.getElementById(sort_id);
        title.className  = '';

        arrow = document.getElementById(arrow_id);
        arrow.style = 'display:none;';
    }

    function showSingle(sort_id, arrow_id){
        title = document.getElementById(sort_id);
        title.className  = 'sorted-by-title';

        arrow = document.getElementById(arrow_id);
        arrow.style = 'display:inline;';
    }

    function onSortingClick(clicked_sort){
        if(clicked_sort == sort) {
            if(order == 0) order = 1;
            else order = 0;
        }
        else{
            order = 0;
        }

        window.location.href = '/cms/order?sort=' + clicked_sort + '&order=' + order + '&page=0';
    }
</script>

<?php require '/app/src/Views/partials/footer.php'; ?>