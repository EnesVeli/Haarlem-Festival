<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Haarlem Jazz</title>

  <!-- Fonts (simple + matches your design rules) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --burgundy:#821315;
      --navy:#10223a;
      --warm:#E9DED8;
      --ink:#1b1f24;
    }

    body{ font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background:#fff; color:var(--ink); }
    h1,h2,h3,h4{ font-family:"Playfair Display", serif; }

    /* Top nav (thin bar like your screenshot) */
    .topbar{
      background: var(--burgundy);
      color: #fff;
      padding: 10px 0;
      font-size: 14px;
    }
    .topbar a{ color:#fff; text-decoration:none; opacity:.95; }
    .topbar a:hover{ opacity:1; text-decoration:underline; }
    .nav-links{ gap:18px; }

    .pill{
      background:#fff;
      color:var(--burgundy);
      border:1px solid rgba(255,255,255,.35);
      border-radius:999px;
      padding:4px 10px;
      font-weight:600;
      font-size:12px;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:8px;
    }

    /* Hero */
    .hero{
      position:relative;
      height: 320px;
      overflow:hidden;
      border-bottom: 4px solid var(--burgundy);
    }
    .hero-placeholder{
      width:100%;
      height:100%;
      background:
        linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,0)),
        #111;
    }
    .hero-overlay{
      position:absolute;
      inset:0;
      background: linear-gradient(to top, rgba(0,0,0,.65), rgba(0,0,0,.25));
    }
    .hero-content{
      position:absolute;
      left: 24px;
      bottom: 22px;
      color:#fff;
      max-width: 560px;
    }
    .hero-content h1{ font-size: 52px; margin:0; }
    .hero-content p{ margin:8px 0 0; opacity:.95; }

    /* Main sections */
    .section-title{
      color: var(--burgundy);
      text-align:center;
      margin: 26px 0 10px;
    }
    .section-sub{
      text-align:center;
      max-width: 760px;
      margin: 0 auto 16px;
      color:#444;
      font-size: 14px;
    }

    .btn-burgundy{
      background:var(--burgundy);
      color:#fff;
      border:1px solid var(--burgundy);
    }
    .btn-burgundy:hover{ background:#6f0f12; border-color:#6f0f12; color:#fff; }

    .btn-outline-burgundy{
      border:1px solid var(--burgundy);
      color:var(--burgundy);
    }
    .btn-outline-burgundy:hover{ background:var(--burgundy); color:#fff; }

    /* Cards */
    .card-soft{
      border: 1px solid rgba(0,0,0,.08);
      border-radius: 10px;
      overflow:hidden;
      background:#fff;
      box-shadow: 0 1px 0 rgba(0,0,0,.02);
    }
    .img-placeholder{
      width:100%;
      background: var(--warm);
      border-bottom: 1px solid rgba(0,0,0,.08);
      position: relative;
      display:block;
    }
    .img-placeholder::after{
      content:"Image";
      position:absolute;
      inset:0;
      display:grid;
      place-items:center;
      color: var(--burgundy);
      font-weight: 600;
      opacity:.6;
    }
    .img-placeholder.exp{ height: 140px; }
    .img-placeholder.artist{ height: 95px; }
    .img-placeholder.map{ height: 240px; }
    .img-placeholder.rec{ height: 140px; }

    /* Experiences row */
    .experiences-row{
      display:flex;
      gap:14px;
      overflow-x:auto;
      padding: 6px 2px 6px;
      scroll-snap-type:x mandatory;
    }
    .experience-item{
      flex: 0 0 240px;
      scroll-snap-align:start;
    }
    .experience-item h3{
      font-size: 16px;
      margin: 12px 14px 6px;
      font-family: "Playfair Display", serif;
    }
    .experience-item p{
      font-size: 12px;
      margin: 0 14px 14px;
      color:#444;
      line-height: 1.35;
    }

    /* Performers grid */
    .artists-grid{
      display:grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 12px;
    }
    @media (max-width: 1200px){ .artists-grid{ grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 768px){ .artists-grid{ grid-template-columns: repeat(2, 1fr); } }

    .artist-name{
      font-size: 13px;
      font-weight: 600;
      text-align:center;
      padding: 8px 10px;
      border-top: 1px solid rgba(0,0,0,.06);
      margin:0;
    }

    /* Maps */
    .maps-wrap{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }
    @media (max-width: 768px){ .maps-wrap{ grid-template-columns: 1fr; } }
    .map-title{
      text-align:center;
      font-family:"Playfair Display", serif;
      color: var(--burgundy);
      margin: 10px 0 6px;
      font-size: 18px;
    }

    /* Footer (simple) */
    .footer{
      background: var(--burgundy);
      color:#fff;
      padding: 26px 0;
      margin-top: 26px;
    }
    .footer small{ opacity:.9; }
  </style>
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
      <a class="btn btn-burgundy me-2" href="#">Jazz Schedule</a>
      <a class="btn btn-outline-burgundy" href="#">Jazz Tickets</a>
    </div>

    <!-- Experiences -->
    <h3 class="text-center" style="color:var(--burgundy); font-family:'Playfair Display',serif; margin-top:18px;">Jazz Experiences</h3>
    <p class="section-sub" style="margin-bottom:10px;">Discover unique moments that bring Haarlem Jazz to life.</p>

    <div class="experiences-row mb-4">
      <?php
        $experiences = [
          ['Late Night Jam', 'Improvised jam sessions guided by top musicians in the festival. Feels like a smoky underground room.'],
          ['Jazz & Drinks', 'Soft instrumental sets paired with cocktails and lounge seating. Feels like a classy evening in a downtown bar.'],
          ['Vinyl Sessions', 'Rediscover rare jazz records curated by local vinyl experts. Feels like stepping into a vintage record store.'],
          ['Sunset Stage', 'Outdoor performances with golden-hour vibes. Feels like a perfect summer evening soundtrack.'],
        ];
      ?>

      <?php foreach ($experiences as $exp): ?>
        <div class="experience-item card-soft">
          <div class="img-placeholder exp" aria-label="Experience image placeholder"></div>
          <h3><?= htmlspecialchars($exp[0]) ?></h3>
          <p><?= htmlspecialchars($exp[1]) ?></p>
        </div>
      <?php endforeach; ?>
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