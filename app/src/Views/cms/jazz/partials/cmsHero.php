<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var string $buttonText
 * @var string $buttonLink
 */
$title = $title ?? '';
$subtitle = $subtitle ?? '';
$buttonText = $buttonText ?? null;
$buttonLink = $buttonLink ?? null;
?>

<div class="jazz-cms-hero">
    <div class="jazz-cms-hero-content">
        <span class="jazz-cms-eyebrow">ADMIN PANEL</span>
        <h1 class="jazz-cms-title"><?= htmlspecialchars($title) ?></h1>

        <?php if (!empty($subtitle)): ?>
            <p class="jazz-cms-subtitle"><?= htmlspecialchars($subtitle) ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($buttonText) && !empty($buttonLink)): ?>
        <div class="jazz-cms-hero-actions">
            <a href="<?= htmlspecialchars($buttonLink) ?>" class="jazz-cms-btn jazz-cms-btn-preview">
                <?= htmlspecialchars($buttonText) ?>
            </a>
        </div>
    <?php endif; ?>
</div>