<?php
/**
 * Booking page for Pay-As-You-Like Story Events.
 *
 * @var \App\Models\StoryEvent $event
 * @var array $ticketTypes
 */
$formattedDate = date('l, F jS', strtotime($event->start_time));
$startTime = date('H:i', strtotime($event->start_time));
$endTime = date('H:i', strtotime($event->end_time));

$payAsYouLikeTicket = null;
foreach ($ticketTypes as $tt) {
    if ((bool)($tt['is_pay_as_you_like'] ?? 0)) {
        $payAsYouLikeTicket = $tt;
        break;
    }
}

$payAsYouLikeTicketId = isset($payAsYouLikeTicket['type_id']) ? (int)$payAsYouLikeTicket['type_id'] : 0;
$defaultAmount = 10.00;
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
                <span class="stories-booking-selected-time__lang"><?= htmlspecialchars($event->language === 'ENG' ? 'English' : 'Dutch') ?></span>
            </div>
        </div>

        <form action="/cart/add" method="POST" id="bookingForm" aria-label="Pay-as-you-like ticket booking form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="event_id" value="<?= (int)$event->event_id ?>">
            <input type="hidden" name="ticket_type_id" value="<?= $payAsYouLikeTicketId ?>">
            <input type="hidden" name="custom_price" id="customPriceField" value="<?= number_format($defaultAmount, 2, '.', '') ?>">
            <input type="hidden" name="redirect_back" value="/cart">

            <div class="stories-booking-donation-card">
                <h3 class="stories-booking-section-title stories-booking-section-title--italic">Configure Donation</h3>
                <span class="stories-badge stories-badge--type">PAY AS YOU LIKE</span>

                <div class="stories-booking-donation-grid">
                    <div class="stories-booking-donation-col">
                        <strong>Number of Seats</strong>
                        <small>Reserve a spot for your group</small>
                        <div class="stories-booking-donation-qty">
                            <button type="button" class="stories-qty-btn stories-qty-btn--round" id="seatMinus" aria-label="Decrease number of seats">&minus;</button>
                            <input type="number" name="quantity" id="seatInput" value="1" min="1" max="20" readonly aria-label="Number of seats">
                            <button type="button" class="stories-qty-btn stories-qty-btn--round" id="seatPlus" aria-label="Increase number of seats">+</button>
                        </div>
                    </div>

                    <div class="stories-booking-donation-col">
                        <strong>Your Donation</strong> <small>(Per Person)</small>
                        <small>Support the circular economy initiative</small>
                        <div class="stories-booking-donation-amounts">
                            <button type="button" class="stories-donation-btn" data-amount="5">&euro;5</button>
                            <button type="button" class="stories-donation-btn is-active" data-amount="10">&euro;10</button>
                            <button type="button" class="stories-donation-btn" data-amount="15">&euro;15</button>
                            <div class="stories-donation-other">
                                <span>&euro;</span>
                                <input type="number" id="customDonation" placeholder="Other" min="0" step="0.01" aria-label="Enter your custom donation amount in Euros">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stories-booking-summary stories-booking-summary--right">
                <span class="stories-booking-total">
                    <strong>TOTAL</strong>
                    <span class="stories-booking-total__amount" id="totalAmount">&euro;<?= number_format($defaultAmount, 2) ?></span>
                </span>
            </div>

            <div class="stories-booking-actions">
                <a href="/stories/<?= htmlspecialchars($event->slug) ?>" class="stories-booking-cancel">Cancel</a>
                <button type="submit" class="stories-booking-submit" aria-label="Add <?= htmlspecialchars($event->name) ?> tickets to your program">Add to Program</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    var seatInput = document.getElementById('seatInput');
    var customPriceField = document.getElementById('customPriceField');
    var totalAmount = document.getElementById('totalAmount');
    var customDonation = document.getElementById('customDonation');
    var donationBtns = document.querySelectorAll('.stories-donation-btn');
    var selectedAmount = <?= number_format($defaultAmount, 2, '.', '') ?>;

    function updateTotal() {
        var seats = parseInt(seatInput.value, 10) || 1;
        var total = seats * selectedAmount;
        customPriceField.value = selectedAmount.toFixed(2);
        totalAmount.textContent = '€' + total.toFixed(2);
    }

    document.getElementById('seatMinus').addEventListener('click', function() {
        var current = parseInt(seatInput.value, 10) || 1;
        if (current > 1) {
            seatInput.value = current - 1;
            updateTotal();
        }
    });

    document.getElementById('seatPlus').addEventListener('click', function() {
        var current = parseInt(seatInput.value, 10) || 1;
        if (current < 20) {
            seatInput.value = current + 1;
            updateTotal();
        }
    });

    donationBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            donationBtns.forEach(function(b) { b.classList.remove('is-active'); });
            this.classList.add('is-active');
            selectedAmount = parseFloat(this.dataset.amount);
            customDonation.value = '';
            updateTotal();
        });
    });

    customDonation.addEventListener('input', function() {
        var val = parseFloat(this.value);
        if (!isNaN(val) && val >= 0) {
            donationBtns.forEach(function(b) { b.classList.remove('is-active'); });
            selectedAmount = val;
            updateTotal();
        }
    });

    updateTotal();
})();
</script>
