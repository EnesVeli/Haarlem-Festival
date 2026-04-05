<?php
$pageTitle = 'Ticket Scanner';
$pageCSS = 'jazz.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Scanner</title>

    <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($pageCSS) ?>">
    <script src="/assets/js/html5-qrcode.min.js"></script>
</head>
<body>
    <div class="scanner-page">
        <h1 class="scanner-title">Ticket Scanner</h1>
        <p class="scanner-subtitle">Scan the QR code using the phone camera.</p>

        <div class="scanner-card">
            <div id="reader" class="scanner-reader"></div>

            <form method="POST" action="/employee/scan" id="scanForm" class="scanner-form">
                <label for="qr_token" class="scanner-label">Scanned ticket code</label>
                <input type="text" name="qr_token" id="qr_token" class="scanner-input" required>
                <button type="submit" class="scanner-button">Check Ticket</button>
            </form>
        </div>

        <?php if (!empty($result)): ?>
    <?php
    $status = $result['status'] ?? 'error';

    if ($status === 'success') {
        $statusText = 'Valid Ticket';
        $statusIcon = '✓';
    } elseif ($status === 'warning') {
        $statusText = 'Already Scanned';
        $statusIcon = '!';
    } else {
        $statusText = 'Invalid Ticket';
        $statusIcon = '✕';
    }
    ?>

    <div class="scanner-result scanner-result-<?= htmlspecialchars($status) ?>">
        <div class="scanner-result-header">
            <div class="scanner-result-icon"><?= htmlspecialchars($statusIcon) ?></div>
            <div>
                <h2 class="scanner-result-title"><?= htmlspecialchars($statusText) ?></h2>
                <p class="scanner-result-message"><?= htmlspecialchars($result['message']) ?></p>
            </div>
        </div>

        <?php if (!empty($result['ticket'])): ?>
            <div class="scanner-ticket-details">
                <p><strong>Event:</strong> <?= htmlspecialchars($result['ticket']['title']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($result['ticket']['category']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($result['ticket']['event_date']) ?></p>
                <p><strong>Time:</strong> <?= htmlspecialchars($result['ticket']['start_time']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($result['ticket']['location']) ?></p>
                <p><strong>QR:</strong> <?= htmlspecialchars($result['ticket']['qr_token']) ?></p>
            </div>
        <?php endif; ?>

        <form method="GET" action="/employee/scan" style="margin-top: 16px;">
            <button type="submit" class="scanner-button">Scan Next Ticket</button>
        </form>
    </div>
<?php endif; ?>
    </div>

    <?php if (empty($result)): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrInput = document.getElementById('qr_token');
        const scanForm = document.getElementById('scanForm');
        const readerElement = document.getElementById('reader');

        if (typeof Html5Qrcode === 'undefined') {
            readerElement.innerHTML = '<p style="color:white;padding:16px;">QR library did not load.</p>';
            return;
        }

        if (!qrInput || !scanForm || !readerElement) {
            return;
        }

        const html5QrCode = new Html5Qrcode("reader");

        function onScanSuccess(decodedText) {
            qrInput.value = decodedText;

            html5QrCode.stop()
                .then(() => scanForm.submit())
                .catch(() => scanForm.submit());
        }

        Html5Qrcode.getCameras()
            .then(cameras => {
                if (!cameras || cameras.length === 0) {
                    readerElement.innerHTML = '<p style="color:white;padding:16px;">No camera found on this device.</p>';
                    return;
                }

                const cameraId = cameras[0].id;

                html5QrCode.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: 220
                    },
                    onScanSuccess
                ).catch(error => {
                    readerElement.innerHTML = '<p style="color:white;padding:16px;">Could not start camera scanner.</p>';
                    console.log(error);
                });
            })
            .catch(error => {
                readerElement.innerHTML = '<p style="color:white;padding:16px;">Camera access failed. Please allow camera permission.</p>';
                console.log(error);
            });
    });
    </script>
    <?php endif; ?>
</body>
</html>