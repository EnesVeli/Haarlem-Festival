<?php
$pageTitle = htmlspecialchars($detail['page_title']) . " - Haarlem Festival";
$pageCSS = "history-detail.css"; 
require __DIR__ . '/../partials/header.php';
?>

<!-- HERO SECTION -->
<section class="detail-hero" style="background-image: url('/assets/images/<?= htmlspecialchars($detail['hero_image']) ?>');">
    <div class="container">
        <h1><?= htmlspecialchars($detail['page_title']) ?></h1>
        <div class="detail-hero-meta">
            <?php if (!empty($detail['location'])): ?>
            <div class="meta-item">
                <span class="meta-icon">📍</span>
                <span><?= htmlspecialchars($detail['location']) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($detail['founded_year'])): ?>
            <div class="meta-item">
                <span class="meta-icon">📅</span>
                <span>Built: <?= htmlspecialchars($detail['founded_year']) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($detail['style_type'])): ?>
            <div class="meta-item">
                <span class="meta-icon">🏛️</span>
                <span>Style: <?= htmlspecialchars($detail['style_type']) ?></span>
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
<?php if (!empty($gallery)): ?>
<section class="photo-gallery-section">
    <div class="container">
        <h2 class="gallery-title">Photo Gallery</h2>
        <div class="photo-gallery">
            <?php foreach ($gallery as $image): ?>
                <img src="/assets/images/<?= htmlspecialchars($image['image_path']) ?>" 
                     alt="<?= htmlspecialchars($image['caption'] ?? $detail['page_title']) ?>" 
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
                <?php foreach ($sections as $section): ?>
                    <?php if ($section['section_type'] === 'about'): ?>
                        <!-- About Section -->
                        <div class="content-section">
                            <h2 class="section-title"><?= htmlspecialchars($section['section_title']) ?></h2>
                            <?php 
                            $paragraphs = explode("\n\n", $section['content']);
                            foreach ($paragraphs as $paragraph): 
                                if (trim($paragraph)): 
                            ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    
                    <?php elseif ($section['section_type'] === 'special'): ?>
                        <!-- Special Content Box (like Müller Organ) -->
                        <div class="special-content-box">
                            <h3><?= htmlspecialchars($section['section_title']) ?></h3>
                            <?php 
                            $paragraphs = explode("\n\n", $section['content']);
                            foreach ($paragraphs as $paragraph): 
                                if (trim($paragraph)): 
                            ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                            
                            <?php if (!empty($section['image_path'])): ?>
                                <img src="/assets/images/<?= htmlspecialchars($section['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($section['section_title']) ?>">
                            <?php endif; ?>
                        </div>
                    
                    <?php elseif ($section['section_type'] === 'highlight'): ?>
                        <!-- Highlight Section -->
                        <div class="highlight-section">
                            <h3><?= htmlspecialchars($section['section_title']) ?></h3>
                            <?php 
                            $paragraphs = explode("\n\n", $section['content']);
                            foreach ($paragraphs as $paragraph): 
                                if (trim($paragraph)): 
                            ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    
                    <?php elseif ($section['section_type'] === 'history'): ?>
                        <!-- Historical Significance -->
                        <div class="content-section">
                            <h2 class="section-title"><?= htmlspecialchars($section['section_title']) ?></h2>
                            <?php 
                            $paragraphs = explode("\n\n", $section['content']);
                            foreach ($paragraphs as $paragraph): 
                                if (trim($paragraph)): 
                            ?>
                                <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- SIDEBAR -->
            <div class="detail-sidebar">
                <!-- QUICK FACTS -->
                <?php if (!empty($facts)): ?>
                <div class="quick-facts-box">
                    <h3 class="quick-facts-title">Quick Facts</h3>
                    <?php foreach ($facts as $fact): ?>
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
                            <!-- You can replace this with an actual map image or Google Maps embed -->
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
            <!-- Other Highlights -->
            <?php foreach ($otherHighlights as $highlight): ?>
                <?php if (!empty($highlight['slug'])): ?>
                <a href="/history/<?= htmlspecialchars($highlight['slug']) ?>" class="journey-card">
                    <img src="/assets/images/<?= htmlspecialchars($highlight['image']) ?>" 
                         alt="<?= htmlspecialchars($highlight['title']) ?>">
                    <div class="journey-card-body">
                        <h3><?= htmlspecialchars($highlight['title']) ?></h3>
                        <p><?= htmlspecialchars($highlight['description']) ?></p>
                    </div>
                </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Stories in Haarlem -->
            <a href="/stories" class="journey-card">
                <img src="/assets/images/stories-haarlem.jpg" alt="Stories in Haarlem">
                <div class="journey-card-body">
                    <h3>Stories in Haarlem</h3>
                    <p>Guided walking tour through historic Haarlem with local storytellers sharing tales of the city's rich past.</p>
                </div>
            </a>

            <!-- Jazz -->
            <a href="/jazz" class="journey-card">
                <img src="/assets/images/jazz-event.jpg" alt="Jazz">
                <div class="journey-card-body">
                    <h3>Jazz</h3>
                    <p>Interactive magic and illusion show at the famous Teylers Museum, perfect for families and wonder-seekers.</p>
                </div>
            </a>

            <!-- Yummy -->
            <a href="/yummy" class="journey-card">
                <img src="/assets/images/yummy-event.jpg" alt="Yummy">
                <div class="journey-card-body">
                    <h3>Yummy!</h3>
                    <p>Culinary storytelling experience with local chefs and food historians exploring Dutch cuisine traditions.</p>
                </div>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>