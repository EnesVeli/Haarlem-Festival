<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket QR</title>
</head>
<body style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f5f5f5;">
    <div style="background:white;padding:20px;border:1px solid #ddd;border-radius:12px;">
        <img src="<?= htmlspecialchars($vm->qr) ?>" alt="Ticket QR Code" style="width:250px;height:250px;">
    </div>
</body>
</html>