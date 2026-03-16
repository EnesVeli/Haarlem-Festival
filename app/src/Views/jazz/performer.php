<?php

$performer = $vm->performer ?? [];
$appearances = $vm->appearances ?? [];
$highlights = $vm->highlights ?? [];
$tracks = $vm->tracks ?? [];
$locations = $vm->locations ?? [];
$recommendations = $vm->recommendations ?? [];
$user = $vm->currentUser ?? null;

$mainClass = 'jazz-main';
$pageTitle = htmlspecialchars(($performer['name'] ?? 'Performer') . ' - Haarlem Jazz');
$activePage = 'jazz';

$name = $performer['name'] ?? '';
$bio = $performer['bio'] ?? '';
$imagePath = $performer['image_path'] ?? '';
$style = $performer['performance_style'] ?? '';
$eventDate = $performer['event_date_text'] ?? '';
$eventTime = $performer['event_time_text'] ?? '';
$venueName = $performer['venue_name'] ?? '';
$venueAddress = $performer['venue_address'] ?? '';
$priceText = $performer['price_text'] ?? '';
$noteText = $performer['note_text'] ?? '';
$audioUrl = $performer['audio_url'] ?? '';

$firstAppearance = $appearances[0] ?? null;

require __DIR__ . '/../partials/header.php';
?>

<style>
<?php include '/app/public/assets/css/jazz.css'; ?>
</style>

<main class="container perf-page">

    <a class="perf-back-link" href="/jazz">← Back to Jazz Main Page</a>

    <?php
$heroBannerImage = $performer['hero_image_path'] ?? ($performer['image_path'] ?? '');
?>

<section class="perf-hero-banner">
    <?php if (!empty($heroBannerImage)): ?>
        <div
            class="perf-hero-banner-bg"
            style="background-image: url('<?= htmlspecialchars($heroBannerImage) ?>');">
        </div>
    <?php else: ?>
        <div class="perf-hero-banner-bg"></div>
    <?php endif; ?>

    <div class="perf-hero-banner-shade"></div>

    <div class="perf-hero-banner-text">
        <h1><?= htmlspecialchars($name) ?></h1>
        <p><?= htmlspecialchars($style ?: 'Live jazz performance during Haarlem Jazz.') ?></p>
    </div>
</section>

    <section class="perf-intro-copy">
        <p><?= htmlspecialchars($bio) ?></p>
    </section>

    <section class="perf-black-panel">
        <div class="perf-black-grid">

            <div class="perf-black-left">
                <div class="perf-image-stack">
                    <div class="perf-stack-image">
                        <?php if (!empty($imagePath)): ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($name) ?>">
                        <?php endif; ?>
                    </div>

                    <div class="perf-stack-image">
                        <?php if (!empty($imagePath)): ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($name) ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="perf-black-text">
                    <h2>Who Are <?= htmlspecialchars($name) ?>?</h2>

                    <div class="perf-description-text">
                        <?= nl2br(htmlspecialchars($bio)) ?>
                    </div>

                    <h3>Genre / Performance Style</h3>

                    <div class="perf-style-cards">
                        <div class="perf-style-card">
                            <div class="perf-style-card-title">Genre</div>
                            <div class="perf-style-card-value">
                                <?= htmlspecialchars($style ?: 'Jazz') ?>
                            </div>
                        </div>

                        <div class="perf-style-card">
                            <div class="perf-style-card-title">Performance Style</div>
                            <div class="perf-style-card-value">
                                <?= htmlspecialchars($style ?: 'Live performance') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="perf-details-card">
                <h2>Event Details</h2>
                <p class="perf-details-subtitle">Secure your spot for this performance.</p>

                <?php if ($firstAppearance): ?>
                    <div class="perf-detail-row">
                        <strong><?= htmlspecialchars($firstAppearance['day_text'] ?? '') ?></strong>
                        <div><?= htmlspecialchars($firstAppearance['time_text'] ?? '') ?></div>
                    </div>

                    <div class="perf-detail-row">
                        <strong><?= htmlspecialchars($firstAppearance['location_text'] ?? $venueName) ?></strong>
                        <div><?= htmlspecialchars($firstAppearance['note_text'] ?? $venueAddress) ?></div>
                        <a href="#maps">View on map</a>
                    </div>
                <?php else: ?>
                    <div class="perf-detail-row">
                        <strong><?= htmlspecialchars($eventDate) ?></strong>
                        <div><?= htmlspecialchars($eventTime) ?></div>
                    </div>

                    <div class="perf-detail-row">
                        <strong><?= htmlspecialchars($venueName) ?></strong>
                        <div><?= htmlspecialchars($venueAddress) ?></div>
                        <a href="#maps">View on map</a>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="perf-ticket-price">
                    <span>TICKET PRICE</span>
                    <strong><?= htmlspecialchars($priceText ?: '€15') ?></strong>
                </div>

                <div class="perf-ticket-note">
                    <?= htmlspecialchars($noteText ?: 'Also available for FREE on Sunday at Grote Markt.') ?>
                </div>

                <button type="button" class="perf-reserve-button">Reserve</button>
            </aside>

        </div>
    </section>

    <section class="perf-bottom-grid">
        <div class="perf-highlights-column">
            <h2>Career Highlights</h2>

            <?php if (!empty($highlights)): ?>
                <?php foreach ($highlights as $highlight): ?>
                    <div class="perf-highlight-block">
                        <h3><?= htmlspecialchars($highlight['title'] ?? '') ?></h3>
                        <p><?= htmlspecialchars($highlight['description'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No highlights found.</p>
            <?php endif; ?>
        </div>

        <aside class="perf-tracks-card">
            <h2>Famous Tracks / Albums</h2>

            <?php if (!empty($tracks)): ?>
                <?php foreach ($tracks as $track): ?>
                    <div class="perf-track-row">
                        <div class="perf-track-cover">
                            <?php if (!empty($track['image_path'])): ?>
                                <img src="<?= htmlspecialchars($track['image_path']) ?>" alt="<?= htmlspecialchars($track['title'] ?? '') ?>">
                            <?php endif; ?>
                        </div>

                        <div class="perf-track-text">
                            <div class="perf-track-name"><?= htmlspecialchars($track['title'] ?? '') ?></div>
                            <div class="perf-track-date"><?= htmlspecialchars($track['release_date_text'] ?? '') ?></div>
                            <div class="perf-track-description"><?= htmlspecialchars($track['description'] ?? '') ?></div>

                            <?php if (!empty($track['listen_url'])): ?>
                                <a class="perf-track-listen" href="<?= htmlspecialchars($track['listen_url']) ?>" target="_blank">Listen now</a>
                            <?php else: ?>
                                <button type="button" class="perf-track-listen">Listen now</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No tracks found.</p>
            <?php endif; ?>
        </aside>
    </section>

    <?php if (!empty($audioUrl)): ?>
        <section class="perf-audio-section">
            <div class="perf-audio-label">
                🎵 Listen to <?= htmlspecialchars($name ?: 'the artist') ?>
            </div>

            <audio class="perf-audio-player" controls preload="none">
                <source src="<?= htmlspecialchars($audioUrl) ?>" type="audio/mpeg">
            </audio>
        </section>
    <?php endif; ?>

    <section class="perf-appearances-section">
        <h2>Appearances During The Haarlem Jazz Event</h2>

        <?php if (!empty($appearances)): ?>
            <?php foreach ($appearances as $appearance): ?>
                <div class="perf-appearance-item">
                    <span class="perf-appearance-day"><?= htmlspecialchars($appearance['day_text'] ?? '') ?>:</span>
                    <?= htmlspecialchars($appearance['time_text'] ?? '') ?>
                    <?php if (!empty($appearance['location_text'])): ?>
                        @ <?= htmlspecialchars($appearance['location_text']) ?>
                    <?php endif; ?>
                    <?php if (!empty($appearance['note_text'])): ?>
                        (<?= htmlspecialchars($appearance['note_text']) ?>)
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="perf-appearance-item">
                <span class="perf-appearance-day"><?= htmlspecialchars($eventDate) ?>:</span>
                <?= htmlspecialchars($eventTime) ?>
                <?php if (!empty($venueName)): ?>
                    @ <?= htmlspecialchars($venueName) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a class="btn btn-burgundy mt-3" href="/jazz/schedule">Explore Jazz Schedule</a>
    </section>

    <section id="maps" class="perf-maps-section">
        <div class="maps-wrap">
            <?php if (!empty($locations)): ?>
                <?php foreach ($locations as $location): ?>
                    <div class="card-soft map-card">
                        <div class="map-title"><?= htmlspecialchars($location['name'] ?? '') ?></div>
                        <div class="map-frame">
                            <iframe
                                src="<?= htmlspecialchars($location['google_maps_embed_url'] ?? '') ?>"
                                loading="lazy"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-muted">No locations found.</div>
            <?php endif; ?>
        </div>
    </section>

    <h3 class="perf-more-like-title">You might also like...</h3>

    <div class="row g-3 mt-1 mb-4">
        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $recommendation): ?>
                <div class="col-12 col-md-4">
                    <a class="card-soft d-block h-100 text-decoration-none" href="<?= htmlspecialchars($recommendation['url'] ?? '#') ?>">

                        <?php if (!empty($recommendation['image_path'])): ?>
                            <img
                                src="<?= htmlspecialchars($recommendation['image_path']) ?>"
                                alt="<?= htmlspecialchars($recommendation['title'] ?? 'Recommendation') ?>"
                                class="img-placeholder rec"
                            >
                        <?php else: ?>
                            <div class="img-placeholder rec"></div>
                        <?php endif; ?>

                        <div class="p-3">
                            <h4 class="perf-more-like-card-title">
                                <?= htmlspecialchars($recommendation['title'] ?? '') ?>
                            </h4>

                            <p class="text-muted mb-0" style="font-size:12px;">
                                <?= htmlspecialchars($recommendation['description'] ?? '') ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-muted">No recommendations found.</div>
        <?php endif; ?>
    </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>