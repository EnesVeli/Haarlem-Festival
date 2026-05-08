<?php
$pageTitle = 'My Personal Program - The Festival Haarlem';
$pageCSS = "payment.css";

/** @var \App\ViewModels\Cart\CartViewModel $view_model */
/** @var ?string $error_message */
?>

<?php require '/app/src/Views/partials/header.php';?>

<div class="pay-main">
    <div class="pay-fail-card">
        <h2 class="pay-title-fail">Your payment were not finished</h2>
        <div class="pay-text">You can finish it at any time, in your personal program. Or cancel it if you wish to do so.</div>
        <div class="pay-button-container">
            <a class="pay-view-button" href="/program">View Your Order</a>            
            <a class="pay-grey-button" href="/tickets">Continue Browsing</a>            
        </div>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>