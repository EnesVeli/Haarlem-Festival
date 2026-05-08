<?php
$pageTitle = 'My Personal Program - The Festival Haarlem';
$pageCSS = "payment.css";

/** @var \App\ViewModels\Cart\CartViewModel $view_model */
/** @var ?string $error_message */
?>

<?php require '/app/src/Views/partials/header.php';?>

<div class="pay-main">
    <div class="pay-success-card">
        <h2 class="pay-title-success">Payment successfull!</h2>
        <div class="pay-text">Your payment was successful. Thank you for your purchase.</div>
        <div class="pay-button-container">
            <a class="pay-gold-button" href="/program">View Your Order</a>            
            <a class="pay-grey-button" href="/tickets">Continue Browsing</a>            
        </div>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>