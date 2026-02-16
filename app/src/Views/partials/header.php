<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'The Festival Haarlem') ?></title>
    
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/partials/header.css">
    
    <!-- Page-specific CSS -->
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="/assets/css/<?= $pageCSS ?>">
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="top-nav">
        <div class="nav-container">
            <a href="/" class="logo">
                TheFestival
            </a>
            
            <ul class="nav-links">
                <li><a href="/" class="active">Home</a></li>
                <li><a href="/tickets">Tickets</a></li>
                <li><a href="/history">History</a></li>
                <li><a href="/stories">Story</a></li>
                <li><a href="/food">Yummy</a></li>
                <li><a href="/jazz">Jazz</a></li>
                <li><a href="/dance">Dance</a></li>
            </ul>
            
            <div class="nav-actions">
                <div class="language-toggle">
                    <a href="?lang=en" class="active">EN</a>
                    <span>|</span>
                    <a href="?lang=nl">NL</a>
                </div>
                
                <?php if (isset($user) && $user): ?>
                    <a href="/program" class="btn-nav btn-nav-outline">My Program</a>
                <?php else: ?>
                    <a href="/login" class="btn-nav btn-nav-outline">Login</a>
                <?php endif; ?>
                
                <a href="/cart" class="cart-icon">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge">0</span>
                </a>
                
                <div class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>

    <main>