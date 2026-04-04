<?php
/** @var \App\ViewModels\HistoryIndexViewModel $viewModel */
$pageTitle = "Book Your Guided Tour - Haarlem Festival";
$pageCSS   = "history.css";
require __DIR__ . '/../partials/header.php';

$selectedDate = $_GET['date'] ?? 'Thursday';
$selectedTime = $_GET['time'] ?? '';
?>

<style>
.booking-back { padding: 14px 0; font-size: .9rem; }
.booking-back a { color: #8b1a1a; text-decoration: none; }
.booking-back a:hover { text-decoration: underline; }
.booking-wrapper { background: #f8f5f0; min-height: 60vh; padding: 40px 0 60px; }
.booking-card { background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 2px 16px rgba(0,0,0,.08); }
.booking-title { font-family: 'Playfair Display', serif; color: #8b1a1a; font-size: 2rem; margin-bottom: 4px; }
.booking-subtitle { color: #8b1a1a; font-size: 1.1rem; font-weight: 600; margin-bottom: 32px; }
.section-divider { border: none; border-top: 1px solid #e8e0d5; margin: 24px 0; }

/* Tour info box */
.tour-info-box { border: 2px solid #c9a84c; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
.tour-info-row { display: flex; gap: 8px; margin-bottom: 8px; font-size: .95rem; }
.tour-info-row:last-child { margin-bottom: 0; }
.tour-info-label { color: #555; min-width: 100px; }
.tour-info-value { color: #222; font-weight: 500; }

/* Language selector */
.language-title { color: #c9a84c; font-weight: 600; margin-bottom: 12px; }
.language-option { border: 1.5px solid #ddd; border-radius: 8px; padding: 14px 18px; margin-bottom: 10px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: border-color .2s; }
.language-option:hover, .language-option.selected { border-color: #c9a84c; }
.language-option input[type=radio] { accent-color: #c9a84c; width: 18px; height: 18px; }
.language-name { font-weight: 600; font-size: .95rem; }
.guide-name { color: #8b1a1a; font-weight: 600; font-size: .85rem; margin-top: 2px; }

/* Note box */
.note-box { background: #fffbf0; border-left: 3px solid #c9a84c; border-radius: 4px; padding: 12px 16px; font-size: .85rem; color: #555; margin-top: 20px; }
.note-box strong { display: block; margin-bottom: 4px; }

/* Ticket selector */
.tickets-title { font-size: 1.3rem; font-weight: 700; color: #222; margin-bottom: 20px; }
.ticket-option { border: 1.5px solid #c9a84c; border-radius: 8px; padding: 16px 20px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
.ticket-name { font-weight: 700; font-size: .95rem; }
.ticket-desc { font-size: .8rem; color: #777; margin-top: 2px; }
.ticket-price { font-weight: 700; font-size: 1rem; color: #222; }
.qty-control { display: flex; align-items: center; gap: 10px; margin-left: 16px; }
.qty-btn { width: 28px; height: 28px; border: 1.5px solid #ccc; border-radius: 50%; background: #fff; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; }
.qty-btn:hover { border-color: #8b1a1a; }
.qty-val { font-weight: 600; min-width: 20px; text-align: center; }

/* Summary */
.summary-line { display: flex; justify-content: space-between; font-size: .9rem; color: #555; margin-bottom: 6px; }
.summary-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 1rem; color: #8b1a1a; margin-top: 8px; padding-top: 8px; border-top: 1px solid #eee; }

/* Buttons */
.btn-cancel { border: 1.5px solid #ccc; background: #fff; color: #555; border-radius: 6px; padding: 10px 24px; font-size: .95rem; cursor: pointer; }
.btn-book-confirm { background: #8b1a1a; color: #fff; border: none; border-radius: 6px; padding: 10px 32px; font-size: .95rem; font-weight: 600; cursor: pointer; flex: 1; margin-left: 10px; }
.btn-book-confirm:hover { background: #6e1414; }
</style>

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
            Due to the nature of this walk, participants must be a minimum of 11 years old and no strollers are allowed. Groups will consist of 15 participants + 1 guide.
          </div>
        </div>

        <!-- RIGHT: Ticket selection -->
        <div class="col-md-6">
          <p class="tickets-title">Choose Your Tickets</p>

          <div class="ticket-option">
            <div>
              <div class="ticket-name">Individual Tickets</div>
              <div class="ticket-desc">Per person • Ages 12 and above</div>
            </div>
            <div class="d-flex align-items-center">
              <span class="ticket-price" id="price-individual">€<?= number_format($tickets[0]['price'] ?? 17.50, 2) ?></span>
              <div class="qty-control">
                <button class="qty-btn" onclick="changeQty('individual', -1)">−</button>
                <span class="qty-val" id="qty-individual">1</span>
                <button class="qty-btn" onclick="changeQty('individual', 1)">+</button>
              </div>
            </div>
          </div>

          <div class="ticket-option">
            <div>
              <div class="ticket-name">Family Ticket</div>
              <div class="ticket-desc">For up to 4 people • Best value for families</div>
            </div>
            <div class="d-flex align-items-center">
              <span class="ticket-price" id="price-family">€60.00</span>
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
            <span id="total-price">€<?= number_format($tickets[0]['price'] ?? 17.50, 2) ?></span>
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
const prices = {
  individual: <?= $tickets[0]['price'] ?? 17.50 ?>,
  family: 60.00
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
    lines += `<div class="summary-line"><span>${qty.individual} Individual Ticket${qty.individual > 1 ? 's' : ''} × €${prices.individual.toFixed(2)}</span><span>€${sub.toFixed(2)}</span></div>`;
  }
  if (qty.family > 0) {
    const sub = qty.family * prices.family;
    total += sub;
    lines += `<div class="summary-line"><span>${qty.family} Family Ticket${qty.family > 1 ? 's' : ''} × €${prices.family.toFixed(2)}</span><span>€${sub.toFixed(2)}</span></div>`;
  }

  document.getElementById('summary-lines').innerHTML = lines;
  document.getElementById('total-price').textContent = '€' + total.toFixed(2);
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
  // POST to cart
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/cart/add';
  const fields = {
    csrf_token: '<?= htmlspecialchars($csrfToken) ?>',
    event_id: '<?= $eventId ?>',
    ticket_type_id: '<?= $typeId ?>',
    quantity: qty.individual + qty.family,
    redirect_back: '/cart'
};
  for (const [k, v] of Object.entries(fields)) {
    const input = document.createElement('input');
    input.type = 'hidden'; input.name = k; input.value = v;
    form.appendChild(input);
  }
  document.body.appendChild(form);
  form.submit();
}

updateSummary();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>