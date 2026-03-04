<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Haarlem Jazz</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="/assets/css/jazz.css">
</head>

<body>

  <div class="topbar">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="fw-semibold">TheFestival</div>

      <div class="d-none d-md-flex nav-links">
        <a href="/">Home</a>
        <a href="#">Tickets</a>
        <a href="#">History</a>
        <a href="#">Story</a>
        <a href="#">Yummy</a>
        <a href="/jazz" class="fw-bold text-decoration-underline">Jazz</a>
        <a href="#">Dance</a>
      </div>

      <div class="d-flex gap-2">
        <a class="pill" href="/login">Login</a>
        <a class="pill" href="/register">Register</a>
      </div>
    </div>
  </div>

  <section class="hero">
    <div class="hero-placeholder"></div>
    <div class="hero-overlay"></div>

    <div class="hero-content">
      <h1>Haarlem Jazz</h1>
      <p>Experience the rhythm of Haarlem’s vibrant jazz scene.</p>
    </div>
  </section>

  <main class="container">

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
              <h4 class="experience-title"><?= htmlspecialchars($exp['title']) ?></h4>
              <p class="experience-text"><?= htmlspecialchars($exp['description']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-muted">No experiences found.</div>
      <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-end mt-2">
      <h3 style="color:var(--burgundy); font-family:'Playfair',serif; margin:0;">Performers</h3>
      <div class="text-muted" style="font-size:12px;">Select an artist to view their detail page</div>
    </div>

    <div class="artists-grid mt-3">
      <?php if (!empty($performers)): ?>
        <?php foreach ($performers as $p): ?>
          <a class="artist-link" href="/jazz/performer?id=<?= (int)$p['id'] ?>">
            <div class="card-soft">
              <div class="img-placeholder artist" aria-label="Artist image placeholder"></div>
              <p class="artist-name"><?= htmlspecialchars($p['name']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-muted">No performers found.</div>
      <?php endif; ?>
    </div>

    <p class="text-center mt-4 mb-2" style="font-family:'Playfair',serif;">
      Secure your spot at Haarlem Jazz 2026 — <a href="#" style="color:var(--burgundy); font-weight:700;">Buy your tickets</a> today!
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

    <h3 class="mt-5" style="font-family:'Playfair',serif;">You might also like…</h3>

<div class="row g-3 mt-1 mb-4">
  <?php if (!empty($recommendations)): ?>
    <?php foreach ($recommendations as $rec): ?>
      <div class="col-12 col-md-4">
        <a class="card-soft d-block h-100 text-decoration-none" href="<?= htmlspecialchars($rec['url'] ?? '#') ?>">
          <div class="img-placeholder rec" aria-label="Recommendation image placeholder"></div>
          <div class="p-3">
            <h4 style="font-family:'Playfair',serif; font-size:18px; margin:0 0 6px; color:var(--burgundy);">
              <?= htmlspecialchars($rec['title'] ?? '') ?>
            </h4>
            <p class="text-muted mb-0" style="font-size:12px;">
              <?= htmlspecialchars($rec['description'] ?? '') ?>
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

  <footer class="footer">
    <div class="container">
      <div class="row g-3">
        <div class="col-12 col-md-3">
          <div class="fw-semibold">The Festival</div>
          <small>Week 30, Thu–Sunday<br>Haarlem, Netherlands</small>
        </div>
        <div class="col-12 col-md-3">
          <div class="fw-semibold">Events</div>
          <small>Haarlem Jazz<br>Dance<br>Yummy</small>
        </div>
        <div class="col-12 col-md-3">
          <div class="fw-semibold">Visitor Info</div>
          <small>Getting here<br>Accessibility<br>FAQ</small>
        </div>
        <div class="col-12 col-md-3">
          <div class="fw-semibold">Stay Updated</div>
          <small>Subscribe to our newsletter</small>
          <div class="d-flex gap-2 mt-2">
            <input class="form-control form-control-sm" placeholder="Your email" />
            <button class="btn btn-light btn-sm fw-semibold">Subscribe</button>
          </div>
        </div>
      </div>

      <hr class="border-light opacity-25 my-3">

      <small>© 2026 The Festival Haarlem. All rights reserved.</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>