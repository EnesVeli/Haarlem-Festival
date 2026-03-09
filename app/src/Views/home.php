<?php
$user        = $user        ?? null;
$homeContent = $homeContent ?? [];
$eventCards  = $eventCards  ?? [];
$venueList   = $venueList   ?? [];
$pageTitle   = 'Home - The Festival Haarlem';

require __DIR__ . '/partials/header.php';
?>
<link href="/assets/css/home.css" rel="stylesheet">

<!-- ===== HERO ===== -->
<section class="hero-main" style="background-image: url('/assets/css/uploads/history/<?= htmlspecialchars($homeContent['hero_image'] ?? 'Heroimage.png') ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content-wrapper">
        <h1 class="hero-title">THE FESTIVAL</h1>
        <p class="hero-subtitle">5 Events • 4 Days • One Haarlem</p>
        <p class="hero-desc">Discover the vibrant heart of Haarlem this July during The Festival, a unique four day celebration that transforms our historic city into a stage for culture, music, and culinary excellence.</p>
        <div class="hero-buttons">
            <a href="#events" class="btn btn-hero-primary">Explore Events</a>
            <a href="/program" class="btn btn-hero-outline">Build My Program</a>
        </div>
        <div class="hero-meta">
            <span><i class="bi bi-calendar3"></i> Week 30 | Thursday – Sunday</span>
            <span><i class="bi bi-geo-alt"></i> Haarlem, Netherlands</span>
        </div>
    </div>
</section>

<!-- ===== WHAT IS MY PROGRAM (comes first after hero) ===== -->
<section class="how-to-use">
    <div class="container">
        <h2 class="section-heading">
            <?= htmlspecialchars($homeContent['program_title'] ?? 'What Is My Program?') ?>
        </h2>
        <p class="section-description">
            <?= htmlspecialchars($homeContent['program_description'] ?? '') ?>
        </p>
        <div class="how-to-steps">
            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Explore Events</h4>
                <p>Browse through our six unique events and discover what interests you most. From jazz to history, there's something for everyone.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Build Your Program</h4>
                <p>Create your personal festival schedule. Add all kinds of events to your wish list.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Book And Enjoy</h4>
                <p>Reserve your tickets and make other reservations for the festival. Get ready for four unforgettable days in Haarlem!</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== FESTIVAL EVENTS (comes second) ===== -->
<section class="festival-events" id="events">
    <div class="container">
        <h2 class="section-heading">Festival Events</h2>
        <p class="section-description">Whether you are a history buff, a jazz enthusiast, or a foodie, you will find your perfect rhythm in our city.</p>

        <div class="events-grid">
            <?php foreach ($eventCards as $card): ?>
            <div class="event-card">
                <!-- Colored bar with category + icon -->
                <div class="event-card-header <?= htmlspecialchars($card['bg_class']) ?>">
                    <span class="event-category-label"><?= htmlspecialchars($card['category']) ?></span>
                    <?php if (!empty($card['image'])): ?>
                        <img src="/assets/css/uploads/history/<?= htmlspecialchars($card['image']) ?>"
                             alt="<?= htmlspecialchars($card['title']) ?>" class="event-card-img" loading="lazy">
                    <?php else: ?>
                        <i class="bi <?= htmlspecialchars($card['icon']) ?> event-card-icon"></i>
                    <?php endif; ?>
                </div>
                <div class="event-card-body">
                    <h3><?= htmlspecialchars($card['title']) ?></h3>
                    <p class="event-short"><?= htmlspecialchars($card['short_description'] ?? '') ?></p>
                    <p class="event-detail"><?= htmlspecialchars($card['long_description'] ?? '') ?></p>
                    <?php if (!empty($card['venues'])): ?>
                    <p class="event-venues"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($card['venues']) ?></p>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($card['url']) ?>" class="btn btn-explore">
                        Explore <?= htmlspecialchars($card['button_label'] ?? $card['title']) ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== NEWSLETTER ===== -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-box">
            <h3>Stay Updated</h3>
            <p>Subscribe to our newsletter for the latest updates</p>
            <form class="newsletter-form" method="POST" action="/newsletter/subscribe">
                <input type="email" name="email" placeholder="Your email" required>
                <button type="submit" class="btn btn-primary-custom">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>