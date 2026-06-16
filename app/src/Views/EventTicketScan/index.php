<?php
/** @var \App\ViewModels\TicketScanner\TicketScanViewModel $vm */
$result    = $vm->result;
$pageTitle = $vm->pageTitle;
$pageCSS   = $vm->pageCSS;
$user      = \App\Framework\Session::user();
$mainClass = 'scanner-main';

require __DIR__ . '/../partials/header.php';
?>

<script src="/assets/js/html5-qrcode.min.js"></script>

<section class="scanner-section">
    <div class="scanner-card">
        <h1 class="scanner-title">Ticket Scanner</h1>
        <p class="scanner-subtitle">Scan a QR code or enter the ticket code manually</p>

        <?php if (empty($result)): ?>
            <div id="reader"></div>

            <form method="POST" action="/employee/scan" class="scanner-form">
                <input
                    type="text"
                    name="scan_value"
                    id="scan_value"
                    placeholder="Enter ticket code"
                    required
                >
                <button type="submit">Check Ticket</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($result)): ?>
            <div class="scanner-result scanner-result--<?= htmlspecialchars($result->status) ?>">
                <p class="scanner-result__message"><?= htmlspecialchars($result->message) ?></p>

                <?php if (!empty($result->ticket)): ?>
                    <?php $booking = $result->ticket->order_item->booking; ?>
                    <div class="scanner-ticket-info">
                        <div class="scanner-ticket-row">
                            <span class="scanner-ticket-label">Event</span>
                            <span><?= htmlspecialchars($booking->getEventName()) ?></span>
                        </div>
                        <div class="scanner-ticket-row">
                            <span class="scanner-ticket-label">Tickets</span>
                            <span><?= htmlspecialchars($booking->getQuantityString()) ?></span>
                        </div>
                        <div class="scanner-ticket-row">
                            <span class="scanner-ticket-label">Date</span>
                            <span><?= htmlspecialchars($booking->getBookingStartDate()->format('d.m.Y')) ?></span>
                        </div>
                        <div class="scanner-ticket-row">
                            <span class="scanner-ticket-label">Time</span>
                            <span><?= htmlspecialchars($booking->getBookingStartDate()->format('H:i') . ' – ' . $booking->getBookingEndDate()->format('H:i')) ?></span>
                        </div>
                        <div class="scanner-ticket-row">
                            <span class="scanner-ticket-label">Location</span>
                            <span><?= htmlspecialchars($booking->getAddressFull()) ?></span>
                        </div>
                        <div class="scanner-ticket-row">
                            <span class="scanner-ticket-label">Ticket code</span>
                            <span><?= htmlspecialchars($result->ticket->code) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="GET" action="/employee/scan">
                    <button type="submit" class="scanner-next-btn">Scan Next Ticket</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.scanner-main {
    background: var(--neutral-light);
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 3rem 1rem;
}

.scanner-section {
    width: 100%;
    display: flex;
    justify-content: center;
}

.scanner-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    padding: 48px 40px;
    width: 100%;
    max-width: 480px;
}

.scanner-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: var(--burgundy);
    margin-bottom: 8px;
}

.scanner-subtitle {
    color: var(--light-text);
    font-size: 15px;
    margin-bottom: 28px;
}

#reader {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}

.scanner-form {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
}

.scanner-form input {
    flex: 1;
    padding: 12px 16px;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    outline: none;
    transition: border-color var(--transition-base);
}

.scanner-form input:focus {
    border-color: var(--navy);
}

.scanner-form button {
    padding: 12px 20px;
    background: var(--navy);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    font-family: 'Playfair Display', serif;
    cursor: pointer;
    transition: background var(--transition-base);
}

.scanner-form button:hover {
    background: var(--navy-dark);
}

.scanner-result {
    border-radius: 12px;
    padding: 20px;
    margin-top: 8px;
}

.scanner-result--success {
    background: #f0fdf4;
    border: 1.5px solid #86efac;
}

.scanner-result--warning {
    background: #fffbeb;
    border: 1.5px solid #fcd34d;
}

.scanner-result--error {
    background: #fef2f2;
    border: 1.5px solid #fca5a5;
}

.scanner-result__message {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 16px;
    color: var(--dark-text);
}

.scanner-ticket-info {
    border-top: 1px solid var(--border-color);
    padding-top: 16px;
    margin-bottom: 20px;
}

.scanner-ticket-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
    border-bottom: 1px solid #f3f4f6;
}

.scanner-ticket-label {
    font-weight: 600;
    color: var(--medium-text);
}

.scanner-next-btn {
    width: 100%;
    padding: 12px;
    background: var(--burgundy);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    font-family: 'Playfair Display', serif;
    cursor: pointer;
    transition: background var(--transition-base);
}

.scanner-next-btn:hover {
    background: var(--burgundy-dark);
}
</style>

<?php if (empty($result)): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const scanInput = document.getElementById("scan_value");
    const scanner = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(cameras => {
        if (cameras.length > 0) {
            scanner.start(
                cameras[0].id,
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    scanInput.value = decodedText;
                    scanInput.form.submit();
                }
            );
        }
    });
});
</script>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
