<?php

$pageTitle = 'CMS Dashboard';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;
$sections = $vm->sections ?? [];

require __DIR__ . '/../partials/header.php';
?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Festival CMS Dashboard';
$subtitle = 'Manage all festival sections from one central admin page.';
$buttonText = 'Back to Website';
$buttonLink = '/';

require __DIR__ . '/jazz/partials/cmsHero.php';
?>

<div class="jazz-cms-panel">
    <div class="jazz-cms-section">

        <div class="jazz-cms-welcome-card">
            <h2 class="jazz-cms-section-title">CMS Sections</h2>
            <p class="jazz-cms-text">
                Choose a section to manage its content.
            </p>
        </div>

        <div class="cms-dashboard-grid">
            <?php foreach ($sections as $section): ?>
                <a href="<?= htmlspecialchars($section['url'] ?? '#') ?>" class="cms-dashboard-card">
                    <div class="cms-dashboard-card-inner">
                        <span class="cms-dashboard-eyebrow">CMS Section</span>

                        <h3 class="cms-dashboard-title">
                            <?= htmlspecialchars($section['title'] ?? '') ?>
                        </h3>

                        <p class="cms-dashboard-description">
                            <?= htmlspecialchars($section['description'] ?? '') ?>
                        </p>

                        <span class="cms-dashboard-link">Open section →</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>