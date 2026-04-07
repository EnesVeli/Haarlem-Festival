<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<main class="book-main">
    <div class="card-body p-4">
        <div class="alert alert-success">Booking was successfully added to your cart.</div>
        <a class="btn btn-primary" href="/cart">View booking in cart</a>
        <a class="btn btn-primary" href="/yummy/list">Continue browsing restaurants</a>
    </div>
</main>

<?php require '/app/src/Views/partials/footer.php'; ?>