<?php use App\Framework\Session; ?>
<!doctype html>
<html lang="en">
<?php
// app/src/Views/jazz/performer.php

$mainClass  = 'jazz-main'; // makes page full width like jazz home
$pageTitle  = htmlspecialchars(($performer['name'] ?? 'Performer') . ' - Haarlem Jazz');

// header needs $user
$user = $currentUser ?? null;

// optional: highlights Jazz (only works if header supports it)
$activePage = 'jazz';

// load your jazz css (header already loads main.css + header.css)
$extraCss = ['/assets/css/jazz.css?v=3'];

require __DIR__ . '/../partials/header.php';
?>

<div class="container perf-page"></div>
  <main class="container perf-page">

    <a class="perf-back" href="/jazz">← Back to Jazz Main Page</a>

    <!-- HERO -->
    <section class="perf-hero perf-hero--split">
  <div class="perf-hero-left">
    <h1><?= htmlspecialchars($performer['name'] ?? '') ?></h1>
    <p class="perf-tagline">
      <?= htmlspecialchars($performer['tagline'] ?? 'Smooth, soulful lounge jazz — perfect for late-evening festival energy.') ?>
    </p>
  </div>

  <div class="perf-hero-right" aria-hidden="true">
    <div class="perf-hero-strips">
      <div class="perf-strip"></div>
      <div class="perf-strip"></div>
      <div class="perf-strip"></div>
      <div class="perf-strip"></div>
      <div class="perf-strip"></div>
    </div>
  </div>
</section>

    <p class="perf-lead">
      <?= htmlspecialchars($performer['bio'] ?? 'We will add a longer description later.') ?>
    </p>

    <!-- DARK SECTION -->
    <section class="perf-dark">
      <div class="perf-dark-grid">

        <div class="perf-left">
          <div class="perf-gallery" aria-label="Performer images">
            <div class="perf-gimg"></div>
            <div class="perf-gimg"></div>
            <div class="perf-gimg"></div>
          </div>

          <div class="perf-who">
            <h2>Who Are <?= htmlspecialchars($performer['name'] ?? '') ?>?</h2>

            <p class="perf-who-text">
              <?= htmlspecialchars($performer['long_description'] ?? 'We will add a longer description later.') ?>
            </p>

            <h3 class="perf-subtitle">Genre / Performance Style</h3>

            <div class="perf-chips">
              <div class="perf-chip">
                <div class="perf-chip-title">Genre</div>
                <div class="perf-chip-value"><?= htmlspecialchars($performer['genre'] ?? 'Lounge jazz / soul-jazz') ?></div>
              </div>

              <div class="perf-chip">
                <div class="perf-chip-title">Performance Style</div>
                <div class="perf-chip-value"><?= htmlspecialchars($performer['style'] ?? 'Smooth, expressive, intimate') ?></div>
              </div>
            </div>
          </div>
        </div>

        <aside class="perf-card">
          <h2>Event Details</h2>
          <p class="perf-card-sub">Secure your spot for this performance.</p>

          <div class="perf-detail">
            <div class="perf-detail-label"><?= htmlspecialchars($performer['date_text'] ?? 'Saturday, July 29th') ?></div>
            <div class="perf-detail-value"><?= htmlspecialchars($performer['time_text'] ?? '18:00 — 19:00') ?></div>
          </div>

          <div class="perf-detail mt-3">
            <div class="perf-detail-label"><?= htmlspecialchars($performer['venue_text'] ?? 'Patronaat - Main Hall') ?></div>
            <div class="perf-detail-value"><?= htmlspecialchars($performer['address_text'] ?? 'Zijlsingel 2, 2013 DN Haarlem') ?></div>
            <a class="perf-maplink" href="#maps">View on map</a>
          </div>

          <hr class="perf-hr">

          <div class="perf-price">
            <div class="perf-price-label">Ticket Price</div>
            <div class="perf-price-value"><?= htmlspecialchars($performer['price_text'] ?? '€15') ?></div>
          </div>

          <div class="perf-note">
            <?= htmlspecialchars($performer['note_text'] ?? 'Also available for FREE on Sunday at Grote Markt.') ?>
          </div>

          <button class="perf-btn" type="button">Reserve</button>
        </aside>

      </div>
    </section>

    <section class="perf-career-albums">
      <div class="perf-ca-grid">

        <div class="perf-career">
          <h2 class="perf-h2-burgundy">Career Highlights</h2>

          <div class="perf-highlight">
            <h3 class="perf-h3-orange">Formation and Early Success</h3>
            <p>Since their inception, the band has toured extensively, performing at various venues ranging from intimate bars to major festivals. Their relentless touring schedule has helped them build a loyal fan base and gain critical acclaim.</p>
          </div>

          <div class="perf-highlight">
            <h3 class="perf-h3-orange">Debut EP</h3>
            <p>Their self-titled debut EP, released in 2018, was well-received in the Netherlands, establishing them as a promising act in the music scene.</p>
          </div>

          <div class="perf-highlight">
            <h3 class="perf-h3-orange">Popronde 2019</h3>
            <p>In 2019, they were one of the most booked bands during Popronde, a significant traveling music festival in the Netherlands, highlighting their growing popularity.</p>
          </div>

          <div class="perf-highlight">
            <h3 class="perf-h3-orange">International Performances</h3>
            <p>The band has showcased their talent internationally, performing in countries such as the USA, Italy, and even on the Azores Islands, expanding their reach beyond the Netherlands.</p>
          </div>
        </div>

        <aside class="perf-albums-card">
          <h2 class="perf-albums-title">Famous Tracks / Albums</h2>

          <div class="perf-album">
            <div class="perf-album-cover"></div>
            <div class="perf-album-info">
              <div class="perf-album-name">Sex ‘n’ jazz</div>
              <div class="perf-album-date">4 May 2007</div>
              <div class="perf-album-desc">Seductive groove-jazz classic</div>
              <button class="perf-listen-btn" type="button">Listen now</button>
            </div>
          </div>

          <div class="perf-album perf-album--reverse">
            <div class="perf-album-cover"></div>
            <div class="perf-album-info">
              <div class="perf-album-name">Lilywhite Soul</div>
              <div class="perf-album-date">16 September 2011</div>
              <div class="perf-album-desc">Velvet lounge-soul shimmer</div>
              <button class="perf-listen-btn" type="button">Listen now</button>
            </div>
          </div>
        </aside>

      </div>
    </section>

    <section class="perf-audio">
      <div class="perf-audio-inner">
        <div class="perf-audio-label">
          <span class="perf-audio-icon">🎵</span>
          <span>Listen to <?= htmlspecialchars($performer['name'] ?? 'the artist') ?></span>
        </div>

        <audio class="perf-audio-player" controls preload="none">
          <source src="" type="audio/mpeg">
        </audio>
      </div>

    </section>
    <section class="perf-members">
      <h2 class="perf-members-title">Group Members</h2>

      <div class="perf-members-row">
        <div class="perf-member-card">
          <div class="perf-member-name">Liam Vermeer — Saxophone</div>
          <div class="perf-member-text">Soulful sax lines shaping the band’s signature late-night lounge sound.</div>
        </div>

        <div class="perf-member-card">
          <div class="perf-member-name">Mara Klein — Vocals</div>
          <div class="perf-member-text">Smooth, intimate vocals that add emotion and depth to every performance.</div>
        </div>

        <div class="perf-member-card">
          <div class="perf-member-name">Jonas De Wilde — Bass</div>
          <div class="perf-member-text">Creates the relaxed, steady grooves the band is known for.</div>
        </div>

        <div class="perf-member-card">
          <div class="perf-member-name">Eva Rens — Keys</div>
          <div class="perf-member-text">Adds atmospheric textures and modern jazz elements.</div>
        </div>
      </div>
    </section>

    <section class="perf-appearances">
  <h2>Appearances During The Haarlem Jazz Event</h2>

  <div class="appear-line">
    <span class="appear-day">Thursday:</span> 18:00 – 19:00 @ Patronaat - Main Hall
  </div>

  <div class="appear-line">
    <span class="appear-day">Sunday:</span> 20:00 – 21:00 @ Grote Markt (Free Show)
  </div>

  <a class="btn btn-burgundy" href="/jazz/schedule">Explore Jazz Schedule</a>
</section>
    <section id="maps" class="mt-4">
      <div class="maps-wrap mt-3">
        <div class="card-soft map-card">
          <div class="map-title">Patronaat</div>
          <div class="map-frame">
            <iframe
              src="https://www.google.com/maps?q=Patronaat,+Haarlem&output=embed"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              allowfullscreen>
            </iframe>
          </div>
        </div>

        <div class="card-soft map-card">
          <div class="map-title">Grote Markt</div>
          <div class="map-frame">
            <iframe
              src="https://www.google.com/maps?q=Grote+Markt,+Haarlem&output=embed"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              allowfullscreen>
            </iframe>
          </div>
        </div>
      </div>
    </section>

    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>