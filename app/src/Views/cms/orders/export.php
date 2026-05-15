<?
/** @var ?string $error_message */

$pageTitle = 'Export Orders CMS - The Festival Haarlem';
$pageCSS = 'order.css';
?>

<?php require '/app/src/Views/partials/header.php'; ?>

<div class="export-main-card">
    <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    <form id="form" method="post" action="/cms/order/export">
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
            <input id="status" type="checkbox" name="status" onchange="onStatusCheckBoxValueChanged(this)" checked>
        </div>

        <div id="status-extra" class="export-staueses-container">
            <h5>Statuses</h5>

            <div class="export-status-chocies">
                <div>
                    <label for="status_1">Not Paid</label>
                    <input id="status_1" type="checkbox" name="status_1">
                </div>
                <div>
                    <label for="status_2">Paid</label>
                    <input id="status_2" type="checkbox" name="status_2" checked>
                </div>
                <div>
                    <label for="status_3">Cancelled</label>
                    <input id="status_3" type="checkbox" name="status_3">
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
    function onStatusCheckBoxValueChanged(sender){
        if(!sender.checked){
            document.getElementById('status-extra').style = 'display:none;';
        }
        else{
            document.getElementById('status-extra').style = 'display:inline;';
        }       
    }

    function onExportButtonClick(){
        if(!atLeastOneStatChecked()){
            alert("Select at least one option to export.");
            return;
        } 
        if(!atLeastOneStatusChecked()){
            alert("Select at least one status to export.");
            return;
        } 

        document.getElementById('form').submit();
    }

    function atLeastOneStatChecked(){
        if(document.getElementById('user_id').checked) return true;
        if(document.getElementById('user_email').checked) return true;
        if(document.getElementById('user_name').checked) return true;
        if(document.getElementById('order_id').checked) return true;
        if(document.getElementById('date').checked) return true;
        if(document.getElementById('total_price').checked) return true;
        if(document.getElementById('status').checked) return true;

        return false;
    }

    function atLeastOneStatusChecked(){
        if(document.getElementById('status_1').checked) return true;
        if(document.getElementById('status_2').checked) return true;
        if(document.getElementById('status_3').checked) return true;

        return false;
    }
</script>

<?php require '/app/src/Views/partials/footer.php'; ?>