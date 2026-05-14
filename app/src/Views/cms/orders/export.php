<?
/** @var ?string $error_message */

$pageTitle = 'Export Orders CMS - The Festival Haarlem';
$pageCSS = 'order.css';
?>

<?php require '/app/src/Views/partials/header.php'; ?>

<div class="export-main-card">
    <form method="post" action="/cms/order/export">
        <h3 class="export-title">Export Orders</h3>
        <div class="export-text">Select options to import.</div>
        <div class="export-sel-section">
            <label for="user_id">User Id</label>
            <input id="user_id" type="checkbox" name="user_id">
        </div>
        <div class="export-sel-section">
            <label for="user_email">User Email</label>
            <input id="user_email" type="checkbox" name="user_email">
        </div>
        <div class="export-sel-section">
            <label for="user_name">User Name</label>
            <input id="user_name" type="checkbox" name="user_name">
        </div>
        <div class="export-sel-section">
            <label for="order_id">Order Id</label>
            <input id="order_id" type="checkbox" name="order_id">
        </div>
        <div class="export-sel-section">
            <label for="date">Order Date</label>
            <input id="date" type="checkbox" name="date">
        </div>
        <div class="export-sel-section">
            <label for="total_price">Total Price</label>
            <input id="total_price" type="checkbox" name="total_price" checked>
        </div>
        <div class="export-sel-section">
            <label for="status">Status</label>
            <input id="status" type="checkbox" name="status" checked>
        </div>

        <div class="export-staueses-container">
            <h5>Statuses</h5>

            <div class="export-status-chocies">
                <div>
                    <label for="status_1">Not Paid</label>
                    <input type="checkbox" name="status_1">
                </div>
                <div>
                    <label for="status_2">Paid</label>
                    <input type="checkbox" name="status_2" checked>
                </div>
                <div>
                    <label for="status_3">Cancelled</label>
                    <input type="checkbox" name="status_3">
                </div>
            </div>       
        </div>

        <div class="export-actions">
            <a class="export-back-button" href="/cms/order">Go Back</a>
            <button class="export-export-button" type="button" onclick="onExportButtonClick()">Export</button>
        </div>
    </form>
</div>

<script>
    

    function onExportButtonClick(){

    }
</script>

<?php require '/app/src/Views/partials/footer.php'; ?>