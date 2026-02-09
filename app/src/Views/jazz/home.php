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

  <!-- Top bar -->
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

  <!-- Hero -->
  <section class="hero">
    <div class="hero-placeholder"></div>
    <div class="hero-overlay"></div>

    <div class="hero-content">
      <h1>Haarlem Jazz</h1>
      <p>Experience the rhythm of Haarlem’s vibrant jazz scene.</p>
    </div>
  </section>

  <main class="container">

    <!-- Intro -->
    <h2 class="section-title">Welcome to Haarlem Jazz</h2>
    <p class="section-sub">
      Haarlem Jazz celebrates soulful melodies, late-night sessions, and vibrant creativity of local and international artists.
      From smooth lounge sets to energetic stage performances, the festival brings the city to life with warm rhythms and unforgettable moments.
    </p>

    <div class="text-center mb-4">
      <a class="btn btn-burgundy me-2" href="/jazz/schedule">Jazz Schedule</a>
      <a class="btn btn-outline-burgundy" href="/jazz/tickets">Jazz Tickets</a>
    </div>

<!-- Experiences -->
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

    <!-- Performers -->
    <div class="d-flex justify-content-between align-items-end mt-2">
      <h3 style="color:var(--burgundy); font-family:'Playfair Display',serif; margin:0;">Performers</h3>
      <div class="text-muted" style="font-size:12px;">Select an artist to view their detail page</div>
    </div>

    <?php
      $artists = [
        'Evolve','Fox & The Mayors','Gare du Nord','Gumbo Kings','Han Bennink','Jonna Frazer',
        'Chris Allen','Lilith Merlot','Myles Sanko','Ntjam Rosie','Rilan & The Bombardiers','Ruis Soundsystem',
        'Soul Six','The Family XL','The Nordanians','The Tom Thompson','Uncle Sue','Wicked Jazz Sounds'
      ];
    ?>

    <div class="artists-grid mt-3">
      <?php foreach ($artists as $name): ?>
        <div class="card-soft">
          <div class="img-placeholder artist" aria-label="Artist image placeholder"></div>
          <p class="artist-name"><?= htmlspecialchars($name) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Maps -->
    <p class="text-center mt-4 mb-2" style="font-family:'Playfair Display',serif;">
      Secure your spot at Haarlem Jazz 2026 — <a href="#" style="color:var(--burgundy); font-weight:700;">Buy your tickets</a> today!
    </p>

    <div class="maps-wrap mt-3">
      <div class="card-soft p-3">
        <div class="map-title">Patronaat</div>
        <div class="img-placeholder map" aria-label="Map image placeholder"></div>
      </div>

      <div class="card-soft p-3">
        <div class="map-title">Grote Markt</div>
        <div class="img-placeholder map" aria-label="Map image placeholder"></div>
      </div>
    </div>

    <!-- Recommendations -->
    <h3 class="mt-5" style="font-family:'Playfair Display',serif;">You might also like…</h3>

    <?php
      $recs = [
        ['A Stroll Through History', 'Guided walking tour through historic Haarlem with local storytellers.'],
        ['Stories', 'Immerse yourself in Haarlem’s spoken-word acts, storytelling, and narrative performances.'],
        ['Yummy!', 'Explore Dutch cuisine and food history with tastings and local favorites.'],
      ];
    ?>

    <div class="row g-3 mt-1 mb-4">
      <?php foreach ($recs as $rec): ?>
        <div class="col-12 col-md-4">
          <div class="card-soft">
            <div class="img-placeholder rec" aria-label="Recommendation image placeholder"></div>
            <div class="p-3">
              <h4 style="font-family:'Playfair Display',serif; font-size:18px; margin:0 0 6px; color:var(--burgundy);">
                <?= htmlspecialchars($rec[0]) ?>
              </h4>
              <p class="text-muted mb-0" style="font-size:12px;"><?= htmlspecialchars($rec[1]) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
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