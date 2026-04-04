<?php
/**
 * Booking page for Fixed-Price Story Events.
 *
 * @var \App\Models\StoryEvent $event
 * @var array $ticketTypes
 */
$formattedDate = date('l, F jS', strtotime($event->start_time));
$startTime = date('H:i', strtotime($event->start_time));
$endTime = date('H:i', strtotime($event->end_time));

$regularTicket = null;
$haarlempasTicket = null;
foreach ($ticketTypes as $tt) {
    $isPayAsYouLike = (bool)($tt['is_pay_as_you_like'] ?? 0);
    $isHaarlemPas = stripos((string)($tt['name'] ?? ''), 'HaarlemPas') !== false;

    if (!$isPayAsYouLike && !$isHaarlemPas && $regularTicket === null) {
        $regularTicket = $tt;
        continue;
    }

    if (!$isPayAsYouLike && $isHaarlemPas && $haarlempasTicket === null) {
        $haarlempasTicket = $tt;
    }
}

$regularTicketId = isset($regularTicket['type_id']) ? (int)$regularTicket['type_id'] : 0;
$regularTicketPrice = isset($regularTicket['price']) ? (float)$regularTicket['price'] : 0.0;
$haarlempasTicketId = isset($haarlempasTicket['type_id']) ? (int)$haarlempasTicket['type_id'] : 0;
$haarlempasTicketPrice = isset($haarlempasTicket['price']) ? (float)$haarlempasTicket['price'] : ($regularTicketPrice * 0.75);
?>

<div class="stories-booking-page">
    <div class="stories-container">
        <a href="/stories/<?= htmlspecialchars($event->slug) ?>" class="stories-booking-back">
            &larr; Back to Event Details
        </a>

        <h1 class="stories-booking-heading">Complete your reservation</h1>
        <h2 class="stories-booking-category">Stories in Haarlem</h2>

        <div class="stories-booking-event-header">
            <div>
                <h3 class="stories-booking-event-name"><?= htmlspecialchars($event->name) ?></h3>
                <p class="stories-booking-event-meta">
                    <?= $formattedDate ?> &nbsp; <?= htmlspecialchars($event->venue_name) ?>
                </p>
            </div>
            <div class="stories-booking-selected-time">
                <span class="stories-booking-selected-time__label">SELECTED TIME</span>
                <span class="stories-booking-selected-time__value"><?= $startTime ?> - <?= $endTime ?></span>
                <span class="stories-booking-selected-time__lang">
                    <?= htmlspecialchars($event->language === 'ENG' ? 'English' : 'Dutch') ?>
                </span>
            </div>
        </div>

        <h3 class="stories-booking-section-title">Choose Your Ticket</h3>

        <form action="/cart/add" method="POST" id="bookingForm" aria-label="Ticket booking form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="event_id" value="<?= (int)$event->event_id ?>">
            <input type="hidden" name="ticket_type_id" id="ticketTypeIdField" value="<?= $regularTicketId ?>">
            <input type="hidden" name="redirect_back" value="/cart">

            <div class="stories-booking-ticket-card">
                <div class="stories-booking-ticket-info">
                    <strong>Regular Ticket</strong>
                    <small>Per person &bull; <?= htmlspecialchars($event->age_group) ?> and above</small>
                </div>
                <div class="stories-booking-ticket-controls">
                    <span class="stories-booking-price">&euro;<?= number_format($regularTicketPrice, 2) ?></span>
                    <button type="button" class="stories-qty-btn" id="qtyMinus" aria-label="Decrease ticket quantity">&minus;</button>
                    <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="20" readonly aria-label="Number of tickets">
                    <button type="button" class="stories-qty-btn" id="qtyPlus" aria-label="Increase ticket quantity">+</button>
                </div>
            </div>

            <div class="stories-booking-haarlempas-card">
                <label class="stories-booking-haarlempas-label">
                    <input type="checkbox" id="haarlemPasCheck" name="has_haarlempas" value="1">
                    <strong>I have a HaarlemPas</strong>
                    <small>Discount will be applied at checkout.</small>
                </label>
                <div class="stories-booking-haarlempas-code" id="haarlemPasCode" style="display:none;">
                    <label for="haarlemPasInput">Enter your 10 digit code</label>
                    <input type="text" id="haarlemPasInput" name="haarlempas_code" placeholder="1234 5678 90" maxlength="13">
                </div>
            </div>

            <div class="stories-booking-summary">
                <span id="summaryText">
                    1 Ticket &times; &euro;<?= number_format($regularTicketPrice, 2) ?> (<?= htmlspecialchars($event->name) ?>)
                </span>
                <span class="stories-booking-total">
                    <strong>TOTAL</strong>
                    <span class="stories-booking-total__amount" id="totalAmount">&euro;<?= number_format($regularTicketPrice, 2) ?></span>
                </span>
            </div>

            <div class="stories-booking-actions">
                <a href="/stories/<?= htmlspecialchars($event->slug) ?>" class="stories-booking-cancel">Cancel</a>
                <button type="submit" class="stories-booking-submit" aria-label="Add <?= htmlspecialchars($event->name) ?> tickets to your program">
                    Add to Program
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    var qtyInput = document.getElementById('qtyInput');
    var ticketTypeField = document.getElementById('ticketTypeIdField');
    var summaryText = document.getElementById('summaryText');
    var totalAmount = document.getElementById('totalAmount');
    var haarlemCheck = document.getElementById('haarlemPasCheck');
    var haarlemCode = document.getElementById('haarlemPasCode');

    var regularTicketId = <?= $regularTicketId ?>;
    var regularPrice = <?= $regularTicketPrice ?>;
    var haarlemTicketId = <?= $haarlempasTicketId ?>;
    var haarlemPrice = <?= $haarlempasTicketPrice ?>;
    var eventName = <?= json_encode($event->name) ?>;

    function updateTotal() {
        var qty = parseInt(qtyInput.value, 10) || 1;
        var selectedTicketTypeId = regularTicketId;
        var unitPrice = regularPrice;

        if (haarlemCheck.checked && haarlemTicketId > 0) {
            selectedTicketTypeId = haarlemTicketId;
            unitPrice = haarlemPrice;
        }

        var total = qty * unitPrice;
        ticketTypeField.value = String(selectedTicketTypeId);
        summaryText.textContent = qty + ' Ticket × €' + unitPrice.toFixed(2) + ' (' + eventName + ')';
        totalAmount.textContent = '€' + total.toFixed(2);
    }

    document.getElementById('qtyMinus').addEventListener('click', function() {
        var current = parseInt(qtyInput.value, 10) || 1;
        if (current > 1) {
            qtyInput.value = current - 1;
            updateTotal();
        }
    });

    document.getElementById('qtyPlus').addEventListener('click', function() {
        var current = parseInt(qtyInput.value, 10) || 1;
        if (current < 20) {
            qtyInput.value = current + 1;
            updateTotal();
        }
    });

    haarlemCheck.addEventListener('change', function() {
        haarlemCode.style.display = this.checked ? 'flex' : 'none';
        updateTotal();
    });

    updateTotal();
})();
</script>
