<?php
$pageTitle = 'My Personal Program - The Festival Haarlem';

/** @var \App\ViewModels\Cart\CartViewModel $view_model */
/** @var ?string $error_message */
?>

<?php require '/app/src/Views/partials/header.php';?>

<div>
    <div class="alert alert-success">
        <h1>Payment successfull!</h1>
        <div>Your payment were successful. Thank you for the purchase.</div>
        <div>
            <a href="/program">View Your Order</a>            
            <a href="/tickets">Continue browsing</a>            
        </div>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>