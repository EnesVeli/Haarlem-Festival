<?php
/** @var \App\ViewModels\TicketScanner\TicketQrViewModel $vm */
?>

<section class="qr-section">
    <div class="qr-card">
        <h1 class="qr-title">Your Ticket QR Code</h1>
        <p class="qr-subtitle">Show this QR code at the entrance</p>
        <img src="<?= htmlspecialchars($vm->qr) ?>" alt="Ticket QR Code" class="qr-image">
    </div>
</section>

<style>
.qr-main {
    background: var(--neutral-light);
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
}

.qr-section {
    width: 100%;
    display: flex;
    justify-content: center;
}

.qr-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    padding: 48px 40px;
    text-align: center;
    max-width: 380px;
    width: 100%;
}

.qr-title {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: var(--burgundy);
    margin-bottom: 8px;
}

.qr-subtitle {
    color: var(--light-text);
    font-size: 15px;
    margin-bottom: 28px;
}

.qr-image {
    width: 250px;
    height: 250px;
    border-radius: 8px;
}
</style>
