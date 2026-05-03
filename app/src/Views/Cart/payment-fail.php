<?php
$pageTitle = 'My Personal Program - The Festival Haarlem';

/** @var \App\ViewModels\Cart\CartViewModel $view_model */
/** @var ?string $error_message */
?>

<?php require '/app/src/Views/partials/header.php';?>

<div>
    <div class="alert alert-danger">
        <h1>Your payment were not finished</h1>
        <div>You can finish it at any time, in your personal program. Or cancel it if you wish to do so.</div>
        <div>
            <a href="/program">View Your Order</a>            
            <a href="/tickets">Continue browsing</a>            
        </div>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>