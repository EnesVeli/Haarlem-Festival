<?php
/**
 * @var \App\Models\Jazz\JazzPerformer $perf
 * @var ?string $error_message
 */
?>

<link rel="stylesheet" href="/assets/css/stories.css">

<section class="stories-booking-page">
    <div class="stories-container">

        <a href="/jazz/performer?id=<?= (int)$perf->id ?>" class="stories-booking-back">
            &larr; Back to Performer
        </a>

        <h1 class="stories-booking-heading">Complete your reservation</h1>
        <h2 class="stories-booking-category">Haarlem Jazz</h2>

        <?php if (!empty($error_message)): ?>
            <div style="background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c;padding:12px 16px;border-radius:6px;margin-bottom:16px;">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <div class="stories-booking-event-header">
            <div>
                <h3 class="stories-booking-event-name"><?= htmlspecialchars($perf->name) ?></h3>
                <p class="stories-booking-event-meta">
                    <?= htmlspecialchars($perf->date->format('l, F jS')) ?>
                    &nbsp;
                    <?= htmlspecialchars($perf->venue_name ?? '') ?>
                </p>
            </div>
            <div class="stories-booking-selected-time">
                <span class="stories-booking-selected-time__label">PERFORMANCE TIME</span>
                <span class="stories-booking-selected-time__value">
                    <?= htmlspecialchars($perf->start_time->format('H:i') . ' - ' . $perf->end_time->format('H:i')) ?>
                </span>
                <?php if (!empty($perf->note_text)): ?>
                    <span class="stories-booking-selected-time__lang"><?= htmlspecialchars($perf->note_text) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <h3 class="stories-booking-section-title">Choose Your Ticket</h3>

        <form method="POST" action="/jazz/book">
            <input type="hidden" name="performer_id" value="<?= (int)$perf->id ?>">

            <div class="stories-booking-ticket-card">
                <div class="stories-booking-ticket-info">
                    <strong>Regular Ticket</strong>
                    <small>Per person</small>
                </div>
                <div class="stories-booking-ticket-controls">
                    <span class="stories-booking-price" id="price"></span>
                    <button type="button" class="stories-qty-btn" onclick="onMinusClick()" aria-label="Decrease quantity">&minus;</button>
                    <input type="number" name="quantity" id="quant_input" value="1" min="1" max="20" readonly aria-label="Number of tickets">
                    <button type="button" class="stories-qty-btn" onclick="onPlusClick()" aria-label="Increase quantity">+</button>
                </div>
            </div>

            <div class="stories-booking-summary">
                <span id="summaryText"></span>
                <span class="stories-booking-total">
                    <strong>TOTAL</strong>
                    <span class="stories-booking-total__amount" id="totalAmount"></span>
                </span>
            </div>

            <div class="stories-booking-actions">
                <a href="/jazz/performer?id=<?= (int)$perf->id ?>" class="stories-booking-cancel">Cancel</a>
                <button type="submit" class="stories-booking-submit">Add to Program</button>
            </div>
        </form>

    </div>
</section>

<script>
    var ticketPrice = <?= (int)$perf->price ?>;
    var performerName = <?= json_encode($perf->name) ?>;
    var quantity = 1;

    var priceEl   = document.getElementById('price');
    var quantEl   = document.getElementById('quant_input');
    var summaryEl = document.getElementById('summaryText');
    var totalEl   = document.getElementById('totalAmount');

    function updateDisplay() {
        var unit  = ticketPrice / 100;
        var total = unit * quantity;
        priceEl.textContent   = '€' + unit.toFixed(2);
        summaryEl.textContent = quantity + ' Ticket × €' + unit.toFixed(2) + ' (' + performerName + ')';
        totalEl.textContent   = '€' + total.toFixed(2);
        quantEl.value         = quantity;
    }

    function onPlusClick()  { if (quantity < 20) { quantity++; updateDisplay(); } }
    function onMinusClick() { if (quantity >  1) { quantity--; updateDisplay(); } }

    updateDisplay();
</script>
