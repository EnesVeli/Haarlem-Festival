<?php
$pageTitle = "Payment Confirmed - The Festival Haarlem";
$pageCSS   = "confirmation.css";
require __DIR__ . '/../partials/header.php';
?>

<div class="confirm-page">
  <div class="container">

    <!-- Progress Steps -->
    <div class="progress-steps">
      <div class="step">
        <div class="step-circle done">1</div>
        <span class="step-label done">Personal Program</span>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-circle done">2</div>
        <span class="step-label done">Checkout</span>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-circle done">3</div>
        <span class="step-label done">Confirmation</span>
      </div>
    </div>

    <!-- Success -->
    <div class="confirm-center">
      <div class="success-icon">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h1 class="confirm-title">Payment Successful!</h1>
      <p class="confirm-subtitle">Your tickets have been confirmed. A<br>confirmation email is on its way.</p>
    </div>

    <!-- What's Next -->
    <div class="next-card">
      <p class="next-title">What's Next?</p>
      <div class="next-item">
        <div class="next-num">1</div>
        <span class="next-text">Download your tickets from the email.</span>
      </div>
      <div class="next-item">
        <div class="next-num">2</div>
        <span class="next-text">Share your program with friends.</span>
      </div>
    </div>

    <!-- Button -->
    <div style="text-align:center">
      <a href="/" class="btn-home">Return to Home</a>
    </div>

  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>