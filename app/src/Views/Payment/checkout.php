<?php
$pageTitle = "Checkout - The Festival Haarlem";
$pageCSS   = "checkout.css";
require __DIR__ . '/../partials/header.php';

$cartItems = $cartItems ?? [];
$cartTotal = $cartTotal ?? 0;
$userEmail = $_SESSION['email'] ?? '';
?>

<div class="checkout-page">
  <div class="container">

    <!-- Progress Steps -->
    <div class="progress-steps">
      <div class="step">
        <div class="step-circle done">1</div>
        <span class="step-label">Personal Program</span>
      </div>
      <div class="step-line done"></div>
      <div class="step">
        <div class="step-circle active">2</div>
        <span class="step-label active">Checkout</span>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-circle pending">3</div>
        <span class="step-label">Confirmation</span>
      </div>
    </div>

    <div class="row g-4">

      <!-- LEFT: Payment Method -->
      <div class="col-lg-5">
        <div class="checkout-card">
          <h2 style="font-family:'Playfair Display',serif;color:#8b1a1a;font-size:1.4rem;margin-bottom:20px">Payment Method</h2>

          <!-- Tabs -->
          <div class="pay-methods">
            <button class="pay-tab active" onclick="switchTab('credit')">Credit Card</button>
            <button class="pay-tab" onclick="switchTab('ideal')">iDEAL</button>
            <button class="pay-tab" onclick="switchTab('paypal')">PayPal</button>
          </div>

          <form method="POST" action="/checkout/process" id="paymentForm">
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="credit_card">

            <!-- Credit Card Panel -->
            <div class="pay-panel active" id="panel-credit">
              <div class="mb-3">
                <label class="field-label">Card Number</label>
                <input type="text" class="field-input" placeholder="1234 5678 9012 3456"
                       maxlength="19" oninput="formatCard(this)">
                <div class="card-logos">
                  <span class="card-logo visa">VISA</span>
                  <span class="card-logo mc">MC</span>
                  <span class="card-logo amex">AMEX</span>
                </div>
              </div>
              <div class="field-row mb-3">
                <div>
                  <label class="field-label">Expiry</label>
                  <input type="text" class="field-input" placeholder="MM / YY" maxlength="7" oninput="formatExpiry(this)">
                </div>
                <div>
                  <label class="field-label">CVC</label>
                  <input type="text" class="field-input" placeholder="123" maxlength="4">
                </div>
              </div>
              <div class="field-row">
                <div>
                  <label class="field-label">Country</label>
                  <input type="text" class="field-input" placeholder="Netherlands" value="Netherlands">
                </div>
                <div>
                  <label class="field-label">Postal Code</label>
                  <input type="text" class="field-input" placeholder="1234AB">
                </div>
              </div>
            </div>

            <!-- iDEAL Panel -->
            <div class="pay-panel" id="panel-ideal">
              <div class="mb-3">
                <label class="field-label">Email Address</label>
                <input type="email" name="email" class="field-input" placeholder="johndoe@gmail.com"
                       value="<?= htmlspecialchars($userEmail) ?>">
              </div>
              <div class="mb-3">
                <label class="field-label">Select a Bank</label>
                <select class="field-input" name="ideal_bank">
                  <option value="">— Select your bank —</option>
                  <option>ABN AMRO</option>
                  <option>ING</option>
                  <option>Rabobank</option>
                  <option>SNS Bank</option>
                  <option>ASN Bank</option>
                  <option>Triodos Bank</option>
                  <option>Bunq</option>
                </select>
              </div>
              <div class="ideal-info">
                After submitting your order, you will be redirected to securely complete your purchase.
              </div>
            </div>

            <!-- PayPal Panel -->
            <div class="pay-panel" id="panel-paypal">
              <div class="paypal-logo">
                <span>Pay<em>Pal</em></span>
              </div>
              <p class="paypal-desc">Pay securely with your PayPal account.<br>
                Click "Pay Now" to continue to PayPal's secure checkout.</p>
              <div class="mb-3">
                <label class="field-label">Email Address</label>
                <input type="email" name="email" class="field-input" placeholder="johndoe@gmail.com"
                       value="<?= htmlspecialchars($userEmail) ?>">
              </div>
            </div>

          </form>
        </div>

        <a href="/cart" class="back-link">← Back to my personal Program</a>
      </div>

      <!-- RIGHT: Order Summary -->
      <div class="col-lg-7">
        <div class="checkout-card">
          <p class="summary-title">Order Summary</p>

          <?php foreach ($cartItems as $item): ?>
          <div class="summary-row">
            <span>
              <?= htmlspecialchars($item['event_name'] ?? $item['name'] ?? 'Event') ?>
              <span class="summary-qty">x <?= (int)$item['quantity'] ?></span>
            </span>
            <strong>€<?= number_format($item['price'] * $item['quantity'], 2) ?></strong>
          </div>
          <?php endforeach; ?>

          <div class="summary-total-row">
            <span>Total</span>
            <span class="summary-total-amount">€<?= number_format($cartTotal, 2) ?></span>
          </div>

          <button class="btn-pay" onclick="submitPayment()">Pay Now</button>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function switchTab(method) {
  document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('active'));
  event.currentTarget.classList.add('active');
  document.getElementById('panel-' + method).classList.add('active');

  const map = { credit: 'credit_card', ideal: 'ideal', paypal: 'paypal' };
  document.getElementById('paymentMethodInput').value = map[method];
}

function formatCard(input) {
  let v = input.value.replace(/\D/g, '').substring(0, 16);
  input.value = v.replace(/(.{4})/g, '$1 ').trim();
}

function formatExpiry(input) {
  let v = input.value.replace(/\D/g, '').substring(0, 4);
  if (v.length >= 2) v = v.substring(0,2) + ' / ' + v.substring(2);
  input.value = v;
}

function submitPayment() {
  document.getElementById('paymentForm').submit();
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>