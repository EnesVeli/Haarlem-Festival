<?php
/**
 * Detail view for Pay-As-You-Like Story Events.
 * Layout: contained hero, subtitle, badges, photo gallery, audio player, sidebar, "You might also like".
 *
 * @var \App\Models\StoryEvent $event
 */
$imagePath     = $event->image_path ?: '/assets/images/stories/venue-placeholder.jpg';
$formattedDate = date('l, F jS', strtotime($event->start_time));
$startTime     = date('H:i', strtotime($event->start_time));
$endTime       = date('H:i', strtotime($event->end_time));

// Collect gallery images (main + up to 2 extras)
$gallery = [$imagePath];
if (!empty($event->gallery_image_1)) $gallery[] = $event->gallery_image_1;
if (!empty($event->gallery_image_2)) $gallery[] = $event->gallery_image_2;
?>

<div class="stories-detail-page">
    <!-- Breadcrumb -->
    <nav class="stories-breadcrumb" aria-label="Breadcrumb">
        <div class="stories-container">
            <a href="/">Home</a> &rsaquo;
            <a href="/stories">Stories in Haarlem</a> &rsaquo;
            <span><?= htmlspecialchars($event->name) ?></span>
        </div>
    </nav>

    <!-- Contained Hero Banner -->
    <div class="stories-container">
        <section class="stories-detail-hero" style="background-image: url('<?= htmlspecialchars($imagePath) ?>');">
            <div class="stories-detail-hero__overlay">
                <h1><?= htmlspecialchars($event->name) ?></h1>
                <?php if ($event->performer_bio): ?>
                <p class="stories-detail-hero__subtitle">
                    <?= htmlspecialchars($event->story_type ? ucfirst($event->story_type) : '') ?></p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Main Content -->
    <div class="stories-container">
        <!-- Subtitle & Badges -->
        <div class="stories-detail-meta">
            <h2 class="stories-detail-subtitle"><?= htmlspecialchars(ucfirst($event->story_type ?? '')) ?></h2>
            <div class="stories-badges">
                <?php if ($event->story_type): ?>
                <span
                    class="stories-badge stories-badge--type"><?= htmlspecialchars(ucfirst($event->story_type)) ?></span>
                <?php endif; ?>
                <span class="stories-badge"><?= htmlspecialchars($event->age_group) ?></span>
                <span class="stories-badge"><?= htmlspecialchars($event->language) ?></span>
            </div>
        </div>

        <!-- Two-Column Grid -->
        <article class="stories-detail-grid">
            <!-- LEFT: Content -->
            <section class="stories-detail-content">
                <!-- Photo Gallery -->
                <div class="stories-gallery">
                    <div class="stories-gallery__main">
                        <img src="<?= htmlspecialchars($gallery[0]) ?>" alt="<?= htmlspecialchars($event->name) ?>"
                            class="stories-detail-img" loading="lazy">
                    </div>
                    <?php if (count($gallery) > 1): ?>
                    <div class="stories-gallery__thumbs">
                        <?php foreach (array_slice($gallery, 1) as $thumb): ?>
                        <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($event->name) ?> gallery"
                            class="stories-gallery__thumb" loading="lazy">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <h2>About the Story</h2>
                <p><?= nl2br(htmlspecialchars($event->description)) ?></p>

                <?php if ($event->performer_name): ?>
                <h2>Career Highlights</h2>
                <p><?= nl2br(htmlspecialchars($event->performer_bio)) ?></p>
                <?php endif; ?>

                <!-- Audio Preview -->
                <?php include __DIR__ . '/../partials/audio_player.php'; ?>
            </section>

            <!-- RIGHT: Sidebar -->
            <aside class="stories-detail-sidebar">
                <!-- Pay As You Like Info -->
                <div class="stories-sidebar-card stories-sidebar-card--pay">
                    <h3>Pay As you like</h3>
                    <p>Some activities are priced <strong>Pay as you like</strong>.
                        We aim to keep these events as accessible as possible so that everyone has the
                        opportunity to participate. We encourage visitors to donate based on how they valued
                        the experience.</p>
                    <p><em>A reservation is required to guarantee entry.</em></p>
                </div>

                <!-- Event Details -->
                <div class="stories-sidebar-card">
                    <h3>Event Details</h3>
                    <p class="stories-sidebar-meta">Reserve your spot for this performance</p>

                    <div class="stories-sidebar-row">
                        <span class="stories-sidebar-label"><?= $formattedDate ?></span>
                        <span class="stories-badge"><?= htmlspecialchars($event->language) ?></span>
                    </div>
                    <div class="stories-sidebar-row">
                        <span><?= $startTime ?> - <?= $endTime ?></span>
                    </div>

                    <p class="stories-sidebar-venue">
                        <strong><?= htmlspecialchars($event->venue_name) ?></strong><br>
                        <?php if ($event->venue_address): ?>
                        <small><?= htmlspecialchars($event->venue_address) ?></small><br>
                        <?php endif; ?>
                        <a href="https://www.google.com/maps/search/<?= urlencode($event->venue_name . ' Haarlem') ?>"
                            target="_blank" rel="noopener">
                            View on map
                        </a>
                    </p>

                    <div class="stories-sidebar-price">
                        <span class="stories-sidebar-price__label">TICKET PRICE</span>
                        <span class="stories-sidebar-price__value">Pay as you like</span>
                    </div>

                    <a href="/stories/<?= htmlspecialchars($event->slug) ?>/book"
                        class="stories-reserve-button">Reserve</a>
                </div>

                <!-- Schedule -->
                <div class="stories-sidebar-card">
                    <h3>Schedule</h3>
                    <p class="stories-sidebar-meta">General schedule of this event</p>

                    <?php if (!empty($schedule)): ?>
                    <div class="stories-schedule-list">
                        <?php foreach ($schedule as $session): ?>
                        <?php 
                                $sDate = date('l, F jS', strtotime($session['start_time']));
                                $sStart = date('H:i', strtotime($session['start_time']));
                                $sEnd = date('H:i', strtotime($session['end_time'])); 
                                ?>
                        <div class="stories-schedule-row stories-schedule-row--dynamic">
                            <span class="stories-schedule-date"><?= $sDate ?></span>
                            <span class="stories-schedule-time"><?= $sStart ?> - <?= $sEnd ?></span>
                            <span
                                class="stories-badge stories-badge--small"><?= htmlspecialchars($session['language'] ?? 'EN') ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="stories-schedule-row stories-schedule-row--dynamic">
                        <span class="stories-schedule-date"><?= $formattedDate ?></span>
                        <span class="stories-schedule-time"><?= $startTime ?> - <?= $endTime ?></span>
                        <span
                            class="stories-badge stories-badge--small"><?= htmlspecialchars($event->language) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </aside>
        </article>

        <?php require __DIR__ . '/../partials/you_might_also_like.php'; ?>
    </div>
</div>