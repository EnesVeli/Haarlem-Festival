<?
/**
 * @var \App\Models\Jazz\JazzPerformer $perf
 * @var ?string $error_message
 */
?>

<main>
    <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/jazz/book">
        <input type="hidden" name="performer_id" value="<?= $perf->id ?>">
        <div class="perf-details-card">
            <div class="perf-ticket-note">
                <?= htmlspecialchars('Also available for FREE on Sunday at Grote Markt.') ?>
            </div>

            <span><?= $perf->name ?> Tickets</span>

            <span><?= $perf->getDateTimeFormated() ?></span>

            <div class="perf-ticket-price">
                <span>TICKET PRICE</span>
                <div>
                    <strong id="price"></strong>

                    <button type="button" class="stories-qty-btn" id="quant_minus" aria-label="Decrease ticket quantity" onclick="onMinusButtonClick()">&minus;</button>
                    <input type="number" name="quantity" id="quant_input" value="1" min="1" max="20" readonly aria-label="Number of tickets">
                    <button type="button" class="stories-qty-btn" id="quant_plus" aria-label="Increase ticket quantity" onclick="onPlusButtonClick()">+</button>
                </div>
            </div>

            <button type="submit" class="perf-reserve-button">Reserve</button>
        </div>
    </form>
</main>

<script>
    var ticket_price = <?= $perf->price ?>;
    var quantity = 1;
    var price_label = document.getElementById("price");
    var quant_input = document.getElementById("quant_input");

    function onPlusButtonClick(){
        if(quantity >= 20) return;

        quantity++;

        quant_input.value = quantity;

        updatePriceLabel();
    }

    function onMinusButtonClick(){
        if(quantity <= 1) return;

        quantity--;

        quant_input.value = quantity;

        updatePriceLabel();
    }

    function updatePriceLabel(){
        price_label.textContent = '€' + (ticket_price * quantity / 100).toFixed(2);
    }

    updatePriceLabel();
</script>