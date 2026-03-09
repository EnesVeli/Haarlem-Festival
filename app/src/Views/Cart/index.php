<?php
// app/src/Views/cart/index.php
$pageTitle = 'Your Cart – The Festival Haarlem';
require __DIR__ . '/../partials/header.php';
?>

<div class="container py-5" style="max-width: 860px;">

    <h1 class="h3 mb-1">Your Cart</h1>
    <p class="text-muted mb-4">Review your tickets before checkout.</p>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($cart['items'])): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart3" style="font-size: 3rem; color: #ccc;"></i>
            <p class="mt-3 text-muted">Your cart is empty.</p>
            <a href="/" class="btn btn-outline-secondary mt-2">Browse events</a>
        </div>
    <?php else: ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Event</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th style="width:130px;">Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart['items'] as $item): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="badge text-bg-secondary text-uppercase mb-1" style="font-size:10px;">
                                    <?= htmlspecialchars($item['event_type']) ?>
                                </span><br>
                                <strong>
                                    <?= htmlspecialchars(ucfirst($item['event_type'])) ?>
                                    – Ticket #<?= (int)$item['event_id'] ?>
                                </strong>
                            </td>
                            <td><?= htmlspecialchars(ucfirst($item['ticket_type'])) ?></td>
                            <td>€<?= number_format((float)$item['price'], 2) ?></td>

                            <!-- Quantity form -->
                            <td>
                                <form method="post" action="/cart/update" class="d-flex align-items-center gap-1">
                                    <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                                    <input
                                        type="number"
                                        name="quantity"
                                        value="<?= (int)$item['quantity'] ?>"
                                        min="1"
                                        max="20"
                                        class="form-control form-control-sm"
                                        style="width:60px;"
                                        onchange="this.form.submit()"
                                    >
                                </form>
                            </td>

                            <td><strong>€<?= number_format($item['subtotal'], 2) ?></strong></td>

                            <!-- Remove -->
                            <td>
                                <form method="post" action="/cart/remove">
                                    <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order summary -->
        <div class="d-flex justify-content-end">
            <div class="card border-0 shadow-sm p-4" style="min-width: 280px;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>€<?= number_format($cart['total'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">VAT (9%)</span>
                    <span>€<?= number_format($cart['total'] * 0.09, 2) ?></span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-4">
                    <strong>Total</strong>
                    <strong>€<?= number_format($cart['total'] * 1.09, 2) ?></strong>
                </div>
                <a href="/checkout" class="btn btn-primary w-100">
                    Proceed to checkout
                </a>
                <a href="/" class="btn btn-link w-100 mt-1 text-muted">
                    Continue browsing
                </a>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>