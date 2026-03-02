<<<<<<< HEAD
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
=======
<?php use App\Framework\Session; ?>
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
  <?php if (Session::isLoggedIn()): ?>
    <span class="pill">
      <?= htmlspecialchars(Session::user()['name'] ?? 'User') ?>
    </span>
    <form method="post" action="/logout" class="m-0">
  <button type="submit" class="pill border-0">Logout</button>
</form>
  <?php else: ?>
    <a class="pill" href="/login">Login</a>
    <a class="pill" href="/register">Register</a>
  <?php endif; ?>
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
>>>>>>> development-
    </div>
</section>

<<<<<<< HEAD
<!-- ===== FESTIVAL EVENTS (comes first) ===== -->
<section class="festival-events" id="events">
=======
    <div class="d-flex justify-content-between align-items-end mt-2">
      <h3 style="color:var(--burgundy); font-family:'Playfair Display',serif; margin:0;">Performers</h3>
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

    <p class="text-center mt-4 mb-2" style="font-family:'Playfair Display',serif;">
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
>>>>>>> development-
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