<?php
/**
 * Booking page for Fixed-Price Story Events.
 *
 * @var \App\Models\StoryEvent $event
 * @var string $slug
 */
$formattedDate = date('l, F jS', strtotime($event->start_time));
$startTime = date('H:i', strtotime($event->start_time));
$endTime = date('H:i', strtotime($event->end_time));

$price = number_format($event->price / 100, 2);
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
                    <?= $formattedDate ?> &nbsp; <?= htmlspecialchars($event->address_name) ?>
                </p>
            </div>
            <div class="stories-booking-selected-time">
                <span class="stories-booking-selected-time__label">SELECTED TIME</span>
                <span class="stories-booking-selected-time__value"><?= $startTime ?> - <?= $endTime ?></span>
                <span class="stories-booking-selected-time__lang">
                    <?= htmlspecialchars($event->language === 'EN' ? 'English' : 'Dutch') ?>
                </span>
            </div>
        </div>

        <h3 class="stories-booking-section-title">Choose Your Ticket</h3>

        <form action="/stories/book/add" method="POST" id="bookingForm" aria-label="Ticket booking form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
            <input type="hidden" name="event_id" value="<?= (int)$event->event_id ?>">
            <input type="hidden" name="slug" value="<?= $slug ?>">
            <input type="hidden" name="pay_as_you_like" value="<?= ($event->is_pay_as_you_like ? 1 : 0) ?>">

            <div class="stories-booking-ticket-card">
                <div class="stories-booking-ticket-info">
                <strong>Regular Ticket</strong>
                    <small>Per person &bull; <?= htmlspecialchars($event->age_group) ?> and above</small>
                </div>
                <div class="stories-booking-ticket-controls">
                    <? if(!$event->is_pay_as_you_like): ?>
                        <span class="stories-booking-price">&euro;<?= $price ?></span>
                    <? endif; ?>
                    <button type="button" class="stories-qty-btn" id="qtyMinus" aria-label="Decrease ticket quantity">&minus;</button>
                    <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="20" readonly aria-label="Number of tickets">
                    <button type="button" class="stories-qty-btn" id="qtyPlus" aria-label="Increase ticket quantity">+</button>
                </div>
            </div>

            <? if(!$event->is_pay_as_you_like): ?>
                <div class="stories-booking-haarlempas-card">
                    <label class="stories-booking-haarlempas-label">
                        <input type="hidden" id="haarlem_pas_input" name="haarlem_pas" value="0">
                        <input type="checkbox" id="haarlemPasCheck" onchange="OnCheckBoxClick(this)">
                        <strong>I have a HaarlemPas</strong>
                        <small>Discount will be applied at checkout.</small>
                    </label>
                    <div id="haarlempas_code_div" class="stories-booking-haarlempas-code" id="haarlemPasCode" style="display:none;">
                        <label for="haarlemPasInput">Enter your 10 digit code</label>
                        <input type="text" id="haarlemPasInput" name="haarlempas_code" placeholder="1234 5678 90" minlength="10" maxlength="10">
                    </div>
                </div>
            <? else: ?>
                <div class="stories-booking-haarlempas-card">
                    <div class="stories-booking-donation-col">
                        <strong>Your Donation</strong> <small>(Per Person)</small>
                        <small>Support the circular economy initiative</small>
                        <div class="stories-booking-donation-amounts">
                            <button type="button" class="stories-donation-btn" data_amount="5" onclick="onPayAmountClick(this)">&euro;5</button>
                            <button id="amount_sel_def" type="button" class="stories-donation-btn is-active" data_amount="10" onclick="onPayAmountClick(this)">&euro;10</button>
                            <button type="button" class="stories-donation-btn" data_amount="15" onclick="onPayAmountClick(this)">&euro;15</button>
                            <button type="button" class="stories-donation-btn" onclick="onCustomPayAmountClick(this)">Custom</button>
                            <div id="custom_pay_amount" class="stories-donation-other" style="display: none;">
                                <span>&euro;</span>
                                <input type="number" id="custom_pay_input" name="pay_as_you_like_amount" placeholder="Other" min="0" max="1000" step="0.01" onchange="onPayYouLikeChanged(this)" aria-label="Enter your custom donation amount in Euros">
                            </div>
                        </div>
                    </div>
                </div>
            <? endif; ?>

            <div class="stories-booking-summary">
                <? if($event->is_pay_as_you_like): ?>
                    <span id="summaryText"></span>
                <? else: ?>
                    <span id="summaryText">
                        1 Ticket &times; &euro;<?= $price ?>
                        (<?= htmlspecialchars($event->name) ?>)
                    </span>
                <? endif; ?>
                <span class="stories-booking-total">
                    <strong>TOTAL</strong>
                    <span class="stories-booking-total__amount"
                        id="totalAmount">&euro;<?= $price ?></span>
                </span>
            </div>

            <div class="stories-booking-actions">
                <a href="/stories/<?= htmlspecialchars($event->slug) ?>" class="stories-booking-cancel">Cancel</a>
                <button type="submit" class="stories-booking-submit"
                    aria-label="Add <?= htmlspecialchars($event->name) ?> tickets to your program">
                    Add to Program
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    var qtyInput = document.getElementById('qtyInput');
    var ticketTypeField = document.getElementById('ticketTypeIdField');
    var summaryText = document.getElementById('summaryText');
    var totalAmount = document.getElementById('totalAmount');
    var haarlemCheck = document.getElementById('haarlemPasCheck');
    var haarlemCode = document.getElementById('haarlemPasCode');

    var pay_as_you_like = <?= (int)$event->is_pay_as_you_like ?>;
    var custom_pay_amount = false;
    var sel_btn = document.getElementById("amount_sel_def");

    var price = <?= $event->price ?>;
    var qnt = 1;

    var eventName = <?= json_encode($event->name) ?>;

    function updateTotal() {
        if(pay_as_you_like == 1){
            totalAmount.textContent = '€' + (document.getElementById('custom_pay_input').value).toFixed(2);
            return; 
        }

        var unitPrice = price;

        if (haarlemCheck.checked) {
            unitPrice = price * 0.75;
        }

        var total = qnt * unitPrice / 100;
        summaryText.textContent = qnt + ' Ticket × €' + (unitPrice / 100).toFixed(2) + ' (' + eventName + ')';
        totalAmount.textContent = '€' + total.toFixed(2);
    }

    function OnCheckBoxClick(checkbox){
        if(checkbox.checked == true){
            document.getElementById('haarlem_pas_input').value = 1;

            document.getElementById('haarlempas_code_div').style = "display: flex;";
        }
        else{
            document.getElementById('haarlem_pas_input').value = 0;

            document.getElementById('haarlempas_code_div').style = "display: none;";
        }

        updateTotal();
    }

    function onPayYouLikeChanged(pay_input){
        if(pay_input.value < 0) pay_input.value = 0;
        if(pay_input.value > 1000) pay_input.value = 1000;

        totalAmount.textContent = '€' + (pay_input.value * 1).toFixed(2);
    }

    function onPayAmountClick(btn){
        amount = btn.getAttribute("data_amount");

        input = document.getElementById('custom_pay_input');
        input.value = amount;
        onPayYouLikeChanged(input);

        if(sel_btn != null){
            sel_btn.setAttribute('class', 'stories-donation-btn');
        }
        btn.setAttribute('class', 'stories-donation-btn is-active');
        sel_btn = btn;

        document.getElementById('custom_pay_amount').style = "display: none";
        custom_pay_amount = false;
    }

    function onCustomPayAmountClick(btn){
        if(custom_pay_amount == false){
            document.getElementById('custom_pay_amount').style = "display: flex";
            custom_pay_amount = true;

            if(sel_btn != null){
                sel_btn.setAttribute('class', 'stories-donation-btn');
            }
            btn.setAttribute('class', 'stories-donation-btn is-active');
            sel_btn = btn;
        }
    }

    document.getElementById('qtyMinus').addEventListener('click', function() {
        if (qnt > 1) {
            qnt--;
            qtyInput.value = qnt;
            updateTotal();
        }
    });

    document.getElementById('qtyPlus').addEventListener('click', function() {
        if (qnt < 20) {
            qnt++;
            qtyInput.value = qnt;
            updateTotal();
        }
    });

    updateTotal();
</script>