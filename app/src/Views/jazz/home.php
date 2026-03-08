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
<section class="hero-main" style="background-image: url('/assets/Images/<?= htmlspecialchars($homeContent['hero_image'] ?? 'Heroimage.png') ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content-wrapper">
        <h1 class="hero-title">THE FESTIVAL</h1>
        <p class="hero-subtitle">5 Events 4 Days One Haarlem</p>
        <p class="hero-location">Week 30 | Thursday – Sunday | Haarlem, Netherlands</p>
        <div class="hero-buttons">
            <a href="#events" class="btn btn-hero-primary">Explore Events</a>
            <a href="/program" class="btn btn-hero-outline">Build My Program</a>
        </div>
    </div>
</section>

<!-- ===== FESTIVAL EVENTS (comes first) ===== -->
<section class="festival-events" id="events">
    <div class="container">
        <h2 class="section-heading">Festival Events</h2>

        <!-- Description paragraphs from DB -->
        <div class="events-description-block">
            <?php if (!empty($homeContent['events_intro'])): ?>
                <p><?= htmlspecialchars($homeContent['events_intro']) ?></p>
            <?php endif; ?>
            <?php if (!empty($homeContent['events_paragraph2'])): ?>
                <p><?= htmlspecialchars($homeContent['events_paragraph2']) ?></p>
            <?php endif; ?>
            <?php if (!empty($homeContent['events_paragraph3'])): ?>
                <p><?= htmlspecialchars($homeContent['events_paragraph3']) ?></p>
            <?php endif; ?>
            <?php if (!empty($homeContent['events_paragraph4'])): ?>
                <p><?= htmlspecialchars($homeContent['events_paragraph4']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Event Cards -->
        <div class="events-grid">
            <?php foreach ($eventCards as $card): ?>
            <div class="event-card">
                <div class="event-image-wrapper">
                    <?php if (!empty($card['image'])): ?>
                        <img src="/assets/images/events/<?= htmlspecialchars($card['image']) ?>"
                             alt="<?= htmlspecialchars($card['title']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="event-placeholder-img <?= htmlspecialchars($card['bg_class']) ?>">
                            <i class="bi <?= htmlspecialchars($card['icon']) ?>"></i>
                        </div>
                    <?php endif; ?>
                    <span class="event-category-badge"><?= htmlspecialchars(strtoupper($card['category'])) ?></span>
                </div>
                <div class="event-card-body">
                    <h3><?= htmlspecialchars($card['title']) ?></h3>
                    <p class="event-short"><?= htmlspecialchars($card['short_description'] ?? '') ?></p>
                    <p class="event-detail"><?= htmlspecialchars($card['long_description']  ?? '') ?></p>
                    <a href="<?= htmlspecialchars($card['url']) ?>" class="btn btn-explore">
                        Explore <?= htmlspecialchars($card['button_label'] ?? $card['title']) ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Venue tags -->
        <?php if (!empty($venueList)): ?>
        <div class="venue-tags-row">
            <?php foreach ($venueList as $venue): ?>
                <span class="venue-tag"><?= htmlspecialchars($venue) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ===== WHAT IS MY PROGRAM (comes second) ===== -->
<section class="how-to-use">
    <div class="container">
        <h2 class="section-heading">
            <?= htmlspecialchars($homeContent['program_title'] ?? 'What Is My Program?') ?>
        </h2>
        <p class="section-description">
            <?= htmlspecialchars($homeContent['program_description'] ?? '') ?>
        </p>
        <h3 class="steps-subheading">How To Use It</h3>
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

<!-- ===== MAP ===== -->
<section class="map-section">
    <div class="container">
        <div class="map-wrapper">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d39360.88!2d4.6369!3d52.3874!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5ef5abfb7e3e5%3A0x300c7888d08e3540!2sHaarlem!5e0!3m2!1sen!2snl!4v1707000000000!5m2!1sen!2snl"
                width="100%" height="420"
                style="border:0; border-radius:12px;"
                allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
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