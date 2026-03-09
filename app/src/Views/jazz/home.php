<?php
// app/src/Views/jazz/home.php

$mainClass = 'jazz-main';
$pageTitle = 'Haarlem Jazz';

// shared header expects $user
$user = $currentUser ?? null;

// header.php I gave you uses $activeNav (not $activePage)
$activeNav = 'jazz';

// load page-specific css
$extraCss = ['/assets/css/jazz.css'];

require __DIR__ . '/../partials/header.php';
?>

<section class="jazz-hero">
  <div class="container">
    <div class="jazz-hero-card">
      <div class="jazz-hero-bg"></div>
      <div class="jazz-hero-shade"></div>

      <div class="jazz-hero-text">
        <h1>Haarlem Jazz</h1>
        <p>Experience the rhythm of Haarlem’s vibrant jazz scene.</p>
      </div>
    </div>
  </div>
</section>

<div class="container">

  <h2 class="section-title">Welcome to Haarlem Jazz</h2>
  <p class="section-sub">
    Haarlem Jazz celebrates soulful melodies, late-night sessions, and vibrant creativity of local and international artists.
    From smooth lounge sets to energetic stage performances, the festival brings the city to life with warm rhythms and unforgettable moments.
  </p>

  <div class="text-center mb-4">
    <a class="btn btn-burgundy me-2" href="/jazz/schedule">Jazz Schedule</a>
    <a class="btn btn-outline-burgundy" href="/jazz/tickets">Jazz Tickets</a>
  </div>

  <h3 class="text-center jazz-section-title mt-4">Jazz Experiences</h3>
  <p class="section-sub mb-2">Discover unique moments that bring Haarlem Jazz to life.</p>

  <div class="experiences-row mb-4">
    <?php if (!empty($experiences)): ?>
      <?php foreach ($experiences as $exp): ?>
        <div class="experience-card">
          <div class="experience-img" aria-label="Experience image placeholder"></div>
          <div class="experience-body">
            <h4 class="experience-title"><?= htmlspecialchars($exp['title'] ?? '') ?></h4>
            <p class="experience-text"><?= htmlspecialchars($exp['body'] ?? '') ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-muted">No experiences found.</div>
    <?php endif; ?>
  </div>

  <div class="d-flex justify-content-between align-items-end mt-2">
    <h3 style="color:var(--burgundy); font-family:'Playfair Display',serif; margin:0;">Performers</h3>
    <div class="text-muted" style="font-size:12px;">Select an artist to view their detail page</div>
  </div>

  <div class="artists-grid mt-3">
  <?php if (!empty($performers)): ?>
    <?php foreach ($performers as $performerBlock): ?>
      <?php
        $performerId = (int)($performerBlock['performer_id'] ?? 0);
        $name = $performerBlock['title'] ?? '';
      ?>
      <a class="artist-link" href="<?= $performerId > 0 ? "/jazz/performer?id=$performerId" : "#" ?>">
        <div class="card-soft">
          <div class="img-placeholder artist" aria-label="Artist image placeholder"></div>
          <p class="artist-name"><?= htmlspecialchars($name) ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-muted">No performers found.</div>
  <?php endif; ?>
</div>

  <p class="text-center mt-4 mb-2" style="font-family:'Playfair Display',serif;">
    Secure your spot at Haarlem Jazz 2026 —
    <a href="#" style="color:var(--burgundy); font-weight:700;">Buy your tickets</a>
    today!
  </p>

  <div class="maps-wrap mt-3">
    <div class="card-soft map-card">
      <div class="map-title">Patronaat</div>
      <div class="map-frame">
        <iframe
          class="map-iframe"
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
          class="map-iframe"
          src="https://www.google.com/maps?q=Grote+Markt,+Haarlem&output=embed"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>

  <h3 class="mt-5" style="font-family:'Playfair Display',serif;">You might also like…</h3>

  <div class="row g-3 mt-1 mb-4">
    <?php if (!empty($recommendations)): ?>
      <?php foreach ($recommendations as $rec): ?>
        <div class="col-12 col-md-4">
          <a class="card-soft d-block h-100 text-decoration-none" href="<?= htmlspecialchars($rec['url'] ?? '#') ?>">
            <div class="img-placeholder rec" aria-label="Recommendation image placeholder"></div>
            <div class="p-3">
              <h4 style="font-family:'Playfair Display',serif; font-size:18px; margin:0 0 6px; color:var(--burgundy);">
                <?= htmlspecialchars($rec['title'] ?? '') ?>
              </h4>
              <p class="text-muted mb-0" style="font-size:12px;">
                <?= htmlspecialchars($rec['body'] ?? '') ?>
              </p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-muted">No recommendations found.</div>
    <?php endif; ?>
  </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>