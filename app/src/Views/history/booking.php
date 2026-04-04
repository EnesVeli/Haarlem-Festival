<?php

$pageTitle = "Book Your Guided Tour - Haarlem Festival";
$pageCSS   = "booking.css";   // loaded by header.php as /assets/css/booking.css
require __DIR__ . '/../partials/header.php';

// Safe price values — fall back to sensible defaults if DB rows are missing
$individualPrice = (float)($individualTicket['price'] ?? 17.50);
$familyPrice     = (float)($familyTicket['price']     ?? 60.00);
?>

<div class="booking-back">
  <div class="container">
    <a href="/history">← Back to A Stroll Through History</a>
  </div>
</div>

<div class="booking-wrapper">
  <div class="container">
    <div class="booking-card">
      <h1 class="booking-title">Book Your Guided Tour</h1>
      <p class="booking-subtitle">Starting point: Bavo Church</p>

      <div class="row g-4">

        <!-- LEFT: Tour info + language -->
        <div class="col-md-6">
          <div class="tour-info-box">
            <div class="tour-info-row">
              <span class="tour-info-label">Date:</span>
              <span class="tour-info-value"><?= htmlspecialchars($selectedDate) ?></span>
            </div>
            <div class="tour-info-row">
              <span class="tour-info-label">Time:</span>
              <span class="tour-info-value"><?= htmlspecialchars($selectedTime) ?></span>
            </div>
            <div class="tour-info-row">
              <span class="tour-info-label">Meeting Point:</span>
              <span class="tour-info-value">Bavo Church</span>
            </div>
          </div>

          <p class="language-title">Select Your Tour Language</p>

          <label class="language-option selected" id="lang-en">
            <div>
              <div class="language-name">English Tour</div>
              <div class="guide-name">Your Guide: <span style="color:#8b1a1a">Williams</span></div>
            </div>
            <input type="radio" name="language" value="en" checked onchange="updateLanguage(this)">
          </label>

          <label class="language-option" id="lang-nl">
            <div>
              <div class="language-name">Dutch Tour</div>
              <div class="guide-name">Your Guide: <span style="color:#8b1a1a">Annet</span></div>
            </div>
            <input type="radio" name="language" value="nl" onchange="updateLanguage(this)">
          </label>

          <label class="language-option" id="lang-zh">
            <div>
              <div class="language-name">Chinese Tour</div>
              <div class="guide-name">Your Guide: <span style="color:#8b1a1a">Kim</span></div>
            </div>
            <input type="radio" name="language" value="zh" onchange="updateLanguage(this)">
          </label>

          <div class="note-box">
            <strong>Please note:</strong>
            Due to the nature of this walk, participants must be a minimum of 11 years old and no
            strollers are allowed. Groups will consist of 15 participants + 1 guide.
          </div>
        </div>

        <!-- RIGHT: Ticket selection -->
        <div class="col-md-6">
          <p class="tickets-title">Choose Your Tickets</p>

          <!-- Individual ticket — price from DB -->
          <div class="ticket-option">
            <div>
              <div class="ticket-name">Individual Tickets</div>
              <div class="ticket-desc">Per person • Ages 12 and above</div>
            </div>
            <div class="d-flex align-items-center">
              <span class="ticket-price" id="price-individual">
                €<?= number_format($individualPrice, 2) ?>
              </span>
              <div class="qty-control">
                <button class="qty-btn" onclick="changeQty('individual', -1)">−</button>
                <span class="qty-val" id="qty-individual">1</span>
                <button class="qty-btn" onclick="changeQty('individual', 1)">+</button>
              </div>
            </div>
          </div>

          <!-- Family ticket — price from DB -->
          <div class="ticket-option">
            <div>
              <div class="ticket-name">Family Ticket</div>
              <div class="ticket-desc">For up to 4 people • Best value for families</div>
            </div>
            <div class="d-flex align-items-center">
              <span class="ticket-price" id="price-family">
                €<?= number_format($familyPrice, 2) ?>
              </span>
              <div class="qty-control">
                <button class="qty-btn" onclick="changeQty('family', -1)">−</button>
                <span class="qty-val" id="qty-family">0</span>
                <button class="qty-btn" onclick="changeQty('family', 1)">+</button>
              </div>
            </div>
          </div>

          <hr class="section-divider">

          <div id="summary-lines"></div>
          <div class="summary-total">
            <span>Total</span>
            <span id="total-price">€<?= number_format($individualPrice, 2) ?></span>
          </div>

          <div class="d-flex mt-4">
            <button class="btn-cancel" onclick="history.back()">Cancel</button>
            <button class="btn-book-confirm" onclick="addToCart()">Add to Program</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
// Prices always injected from PHP (from the DB), never hardcoded
const prices = {
    individual: <?= json_encode($individualPrice) ?>,
    family:     <?= json_encode($familyPrice) ?>
};
const qty = { individual: 1, family: 0 };

function changeQty(type, delta) {
    qty[type] = Math.max(0, qty[type] + delta);
    document.getElementById('qty-' + type).textContent = qty[type];
    updateSummary();
}

function updateSummary() {
    let lines = '';
    let total = 0;

    if (qty.individual > 0) {
        const sub = qty.individual * prices.individual;
        total += sub;
        lines += `<div class="summary-line">
            <span>${qty.individual} Individual Ticket${qty.individual > 1 ? 's' : ''} \u00d7 \u20ac${prices.individual.toFixed(2)}</span>
            <span>\u20ac${sub.toFixed(2)}</span>
        </div>`;
    }
    if (qty.family > 0) {
        const sub = qty.family * prices.family;
        total += sub;
        lines += `<div class="summary-line">
            <span>${qty.family} Family Ticket${qty.family > 1 ? 's' : ''} \u00d7 \u20ac${prices.family.toFixed(2)}</span>
            <span>\u20ac${sub.toFixed(2)}</span>
        </div>`;
    }

    document.getElementById('summary-lines').innerHTML = lines;
    document.getElementById('total-price').textContent = '\u20ac' + total.toFixed(2);
}

function updateLanguage(el) {
    document.querySelectorAll('.language-option').forEach(o => o.classList.remove('selected'));
    el.closest('.language-option').classList.add('selected');
}

function addToCart() {
    if (qty.individual === 0 && qty.family === 0) {
        alert('Please select at least one ticket.');
        return;
    }
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/cart/add';
    const fields = {
        csrf_token:     '<?= htmlspecialchars($csrfToken) ?>',
        event_id:       '<?= (int)$eventId ?>',
        ticket_type_id: '<?= (int)$typeId ?>',
        quantity:       qty.individual + qty.family,
        redirect_back:  '/cart'
    };
    for (const [k, v] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = k;
        input.value = v;
        form.appendChild(input);
    }
    document.body.appendChild(form);
    form.submit();
}

updateSummary();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>