<?php
/** @var \App\ViewModels\HistoryDetailViewModel $viewModel */
$pageTitle = $viewModel->fullPageTitle();
$pageCSS = "history-detail.css"; 
require __DIR__ . '/../partials/header.php';
?>

<!-- HERO SECTION -->
<section class="detail-hero" style="background-image: url('/assets/uploads/History/<?= htmlspecialchars($viewModel->heroImage) ?>');">
    <div class="container">
        <h1><?= htmlspecialchars($viewModel->pageTitle) ?></h1>
        <div class="detail-hero-meta">
            <?php if ($viewModel->hasLocation()): ?>
            <div class="meta-item">
                <span class="meta-icon">📍</span>
                <span><?= htmlspecialchars($viewModel->location) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($viewModel->hasFoundedYear()): ?>
            <div class="meta-item">
                <span class="meta-icon">📅</span>
                <span>Built: <?= htmlspecialchars($viewModel->foundedYear) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($viewModel->hasStyleType()): ?>
            <div class="meta-item">
                <span class="meta-icon">🏛️</span>
                <span>Style: <?= htmlspecialchars($viewModel->styleType) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- BREADCRUMB -->
<nav class="breadcrumb-nav">
    <div class="container">
        <a href="/history" class="back-link">
            ← Back to A Stroll Through History
        </a>
    </div>
</nav>

<!-- PHOTO GALLERY -->
<?php if ($viewModel->hasGallery()): ?>
<section class="photo-gallery-section">
    <div class="container">
        <h2 class="gallery-title">Photo Gallery</h2>
        <div class="photo-gallery">
            <?php foreach ($viewModel->gallery as $image): ?>
                <img src="/assets/uploads/History/<?= htmlspecialchars($image['image_path']) ?>" 
                     alt="<?= htmlspecialchars($image['caption'] ?? $viewModel->pageTitle) ?>" 
                     class="gallery-image">
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- MAIN CONTENT WITH SIDEBAR -->
<section class="section-padding">
    <div class="container">
        <div class="detail-content-wrapper">
            <!-- MAIN CONTENT -->
            <div class="detail-main-content">
                <?php foreach ($viewModel->sections as $section): ?>

                    <?php if ($section['section_type'] === 'about'): ?>
                        <div class="content-section">
                            <h2 class="section-title"><?= htmlspecialchars($section['section_title']) ?></h2>
                            <?php foreach ($viewModel->getParagraphs($section) as $paragraph): ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($section['section_type'] === 'special'): ?>
                        <div class="special-content-box">
                            <h3><?= htmlspecialchars($section['section_title']) ?></h3>
                            <?php foreach ($viewModel->getParagraphs($section) as $paragraph): ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php endforeach; ?>
                            <?php if (!empty($section['image_path'])): ?>
                                <img src="/assets/uploads/History/<?= htmlspecialchars($section['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($section['section_title']) ?>">
                            <?php endif; ?>
                        </div>

                    <?php elseif ($section['section_type'] === 'highlight'): ?>
                        <div class="highlight-section">
                            <h3><?= htmlspecialchars($section['section_title']) ?></h3>
                            <?php foreach ($viewModel->getParagraphs($section) as $paragraph): ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($section['section_type'] === 'history'): ?>
                        <div class="content-section">
                            <h2 class="section-title"><?= htmlspecialchars($section['section_title']) ?></h2>
                            <?php foreach ($viewModel->getParagraphs($section) as $paragraph): ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php endforeach; ?>
                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- SIDEBAR -->
            <div class="detail-sidebar">
                <!-- QUICK FACTS -->
                <?php if ($viewModel->hasFacts()): ?>
                <div class="quick-facts-box">
                    <h3 class="quick-facts-title">Quick Facts</h3>
                    <?php foreach ($viewModel->facts as $fact): ?>
                        <div class="fact-item">
                            <span class="fact-icon"><?= htmlspecialchars($fact['icon']) ?></span>
                            <span class="fact-label"><?= htmlspecialchars($fact['label']) ?></span>
                            <span class="fact-value"><?= htmlspecialchars($fact['value']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- LOCATION ON ROUTE -->
                <div class="location-box">
                    <h3>Location on Route</h3>
                    <div class="map-container">
                        <div class="map-placeholder">
                            <p>Map showing location</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- COMPLETE YOUR JOURNEY -->
<section class="journey-section">
    <div class="container">
        <h2 class="journey-title">Complete Your Journey</h2>
        <div class="journey-grid">
            <?php foreach ($viewModel->otherHighlights as $highlight): ?>
                <a href="/history/<?= htmlspecialchars($highlight['slug']) ?>" class="journey-card">
                    <img src="/assets/uploads/History/<?= htmlspecialchars($highlight['image']) ?>" 
                         alt="<?= htmlspecialchars($highlight['title']) ?>">
                    <div class="journey-card-body">
                        <h3><?= htmlspecialchars($highlight['title']) ?></h3>
                        <p><?= htmlspecialchars($highlight['description']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>

            <a href="/stories" class="journey-card">
                <img src="/assets/uploads/History/stories-haarlem.jpg" alt="Stories in Haarlem">
                <div class="journey-card-body">
                    <h3>Stories in Haarlem</h3>
                    <p>Guided walking tour through historic Haarlem with local storytellers sharing tales of the city's rich past.</p>
                </div>
            </a>

            <a href="/jazz" class="journey-card">
                <img src="/assets/uploads/History/jazz-event.jpg" alt="Jazz">
                <div class="journey-card-body">
                    <h3>Jazz</h3>
                    <p>Interactive magic and illusion show at the famous Teylers Museum, perfect for families and wonder-seekers.</p>
                </div>
            </a>

            <a href="/yummy" class="journey-card">
                <img src="/assets/uploads/History/yummy-event.jpg" alt="Yummy">
                <div class="journey-card-body">
                    <h3>Yummy!</h3>
                    <p>Culinary storytelling experience with local chefs and food historians exploring Dutch cuisine traditions.</p>
                </div>
            </a>

            <a href="/tickets" class="journey-card">
                <img src="/assets/uploads/History/tickets-event.jpg" alt="Tickets">
                <div class="journey-card-body">
                    <h3>Tickets</h3>
                    <p>Browse every festival event and reserve the tickets you need for your personal Haarlem experience.</p>
                </div>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
