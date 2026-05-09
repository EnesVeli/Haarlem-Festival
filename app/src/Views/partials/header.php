<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'The Festival Haarlem') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/partials/header.css">

    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($pageCSS) ?>">
    <?php endif; ?>
</head>

<body>
<?php
if (!isset($user) || !$user) {
    $user = \App\Framework\Session::user();
}

$cartCount = (int)($cartCount ?? App\Framework\Session::getCartItemsCount() ?? 0);
?>

<nav class="top-nav">
    <div class="nav-container">
        <a href="/" class="logo">TheFestival</a>

        <ul class="nav-links">
            <li><a href="/">Home</a></li>
            <li><a href="/tickets">Tickets</a></li>
            <li><a href="/history">History</a></li>
            <li><a href="/stories">Stories</a></li>
            <li><a href="/yummy">Yummy</a></li>
            <li><a href="/jazz">Jazz</a></li>
            <li><a href="/dance">Dance</a></li>

            <?php if ($user && strtolower($user['role'] ?? '') === 'admin'): ?>
                <li><a href="/cms">Dashboard</a></li>
            <?php endif; ?>
        </ul>

        <div class="nav-actions">
            <div class="language-toggle">
                <a href="?lang=en" class="active">EN</a>
                <span>|</span>
                <a href="?lang=nl">NL</a>
            </div>

            <?php if ($user): ?>
                <div class="profile-dropdown">
                    <button class="profile-trigger" id="profileToggle" aria-expanded="false">
                        <div class="profile-avatar profile-avatar-initials">
                            <?= htmlspecialchars(mb_strtoupper(mb_substr($user['name'] ?? 'A', 0, 1))) ?>
                        </div>
                        <span class="profile-name"><?= htmlspecialchars($user['name'] ?? 'Account') ?></span>
                        <i class="bi bi-chevron-down profile-chevron"></i>
                    </button>

                    <div class="profile-menu" id="profileMenu">
                        <a href="/profile" class="profile-menu-item">
                            <i class="bi bi-person"></i> My Profile
                        </a>

                        <?php if (strtolower($user['role'] ?? '') === 'admin'): ?>
                            <div class="profile-menu-divider"></div>

                            <a href="/cms" class="profile-menu-item">
                                <i class="bi bi-speedometer2"></i> CMS Dashboard
                            </a>
                        <?php endif; ?>

                        <div class="profile-menu-divider"></div>

                        <a href="/program" class="profile-menu-item profile-menu-item--danger">
                            <i class="bi bi-box-arrow-right"></i> Personal Program
                        </a>

                        <div class="profile-menu-divider"></div>

                        <a href="/logout" class="profile-menu-item profile-menu-item--danger">
                            <i class="bi bi-box-arrow-right"></i> Log out
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login" class="btn-nav btn-nav-outline">Login</a>
            <?php endif; ?>

            <a href="/cart" class="cart-icon">
                <i class="bi bi-cart3"></i>
                <span class="cart-badge"><?= (int)$cartCount ?></span>
            </a>
        </div>
    </div>
</nav>

<script>
(function () {
    const toggle = document.getElementById('profileToggle');
    const dropdown = toggle?.closest('.profile-dropdown');
    if (!toggle || !dropdown) return;

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function () {
        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
    });
})();
</script>

<main class="<?= htmlspecialchars($mainClass ?? '') ?>">
