<?php
$result = $vm->result;
$pageTitle = $vm->pageTitle;
$pageCSS = $vm->pageCSS;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($pageCSS) ?>">
    <script src="/assets/js/html5-qrcode.min.js"></script>
</head>
<body>

<div class="scanner-page">
    <h1>Ticket Scanner</h1>

    <div id="reader" style="width:300px;"></div>

    <form method="POST" action="/employee/scan">
        <input
            type="text"
            name="scan_value"
            id="scan_value"
            placeholder="Scan QR or enter ticket code"
            required
        >
        <button type="submit">Check Ticket</button>
    </form>

    <?php if (!empty($result)): ?>
        <div>
            <h2><?= htmlspecialchars($result['status']) ?></h2>
            <p><?= htmlspecialchars($result['message']) ?></p>

            <?php if (!empty($result['ticket'])): ?>
                <p><strong>Event:</strong> <?= htmlspecialchars($result['ticket']->title) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($result['ticket']->eventDate) ?></p>
                <p><strong>Time:</strong> <?= htmlspecialchars($result['ticket']->startTime) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($result['ticket']->location) ?></p>
                <p><strong>Ticket code:</strong> <?= htmlspecialchars($result['ticket']->ticketCode) ?></p>
            <?php endif; ?>

            <form method="GET" action="/employee/scan">
                <button type="submit">Scan Next Ticket</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php if (empty($result)): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const scanInput = document.getElementById("scan_value");
    const scanner = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(cameras => {
        if (cameras.length > 0) {
            scanner.start(
                cameras[0].id,
                { fps: 10, qrbox: 200 },
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

</body>
</html>