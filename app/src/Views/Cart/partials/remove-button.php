<?
/** @var \App\Models\OrderItem $item */
?>

<form method="post" action="/cart/remove" class="program-card__remove">
    <input type="hidden" name="item_id" value="<?= $item->item_id ?>">
    <input type="hidden" name="order_id" value="<?= $item->order_id ?>">
    <button type="submit" class="program-card__remove-btn" title="Remove">
        <i class="bi bi-trash"></i> Remove
    </button>
</form>