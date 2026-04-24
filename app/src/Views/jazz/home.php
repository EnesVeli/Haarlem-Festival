<?php

$hero = $vm->hero;
$intro = $vm->intro;
$experiences = $vm->experiences;
$performers = $vm->performers;
$recommendations = $vm->recommendations;
$locations = $vm->locations;
?>

<section class="jazz-hero">
  <div class="container">
    <div class="jazz-hero-card">
      <?php if (!empty($hero?->imagePath)): ?>
        <div
          class="jazz-hero-bg"
          style="background-image: url('<?= htmlspecialchars($hero->imagePath) ?>');">
        </div>
      <?php else: ?>
        <div class="jazz-hero-bg"></div>
      <?php endif; ?>

      <div class="jazz-hero-shade"></div>

      <div class="jazz-hero-text">
        <h1><?= htmlspecialchars($hero?->title ?? 'Haarlem Jazz') ?></h1>
        <p><?= htmlspecialchars($hero?->subtitle ?? 'Experience the rhythm of Haarlem’s vibrant jazz scene.') ?></p>
      </div>
    </div>
  </div>
</section>

<div class="container">

  <h2 class="section-title"><?= htmlspecialchars($intro?->title ?? 'Welcome to Haarlem Jazz') ?></h2>
  <p class="section-sub">
    <?= htmlspecialchars($intro?->description ?? '') ?>
  </p>

  <div class="text-center mb-4">
    <a class="btn btn-outline-burgundy" href="/tickets/jazz">Jazz Tickets</a>
  </div>

  <h3 class="text-center jazz-section-title mt-4">Jazz Experiences</h3>
  <p class="section-sub mb-2">Discover unique moments that bring Haarlem Jazz to life.</p>

  <div class="experiences-row mb-4">
    <?php if (!empty($experiences)): ?>
      <?php foreach ($experiences as $experience): ?>
        <div class="experience-card">

          <?php if (!empty($experience->imagePath)): ?>
            <img
              src="<?= htmlspecialchars($experience->imagePath) ?>"
              alt="<?= htmlspecialchars($experience->title ?: 'Experience') ?>"
              class="experience-img"
            >
          <?php else: ?>
            <div class="experience-img"></div>
          <?php endif; ?>

          <div class="experience-body">
            <h4 class="experience-title"><?= htmlspecialchars($experience->title) ?></h4>
            <p class="experience-text"><?= htmlspecialchars($experience->description) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-muted">No experiences found.</div>
    <?php endif; ?>
  </div>

  <div class="d-flex justify-content-between align-items-end mt-2">
    <h3 style="color:var(--burgundy); font-family:'Playfair Display',serif; margin:0;">
      Performers
    </h3>

    <div class="text-muted" style="font-size:12px;">
      Select an artist to view their detail page
    </div>
  </div>

  <div class="artists-grid mt-3">
    <?php if (!empty($performers)): ?>
      <?php foreach ($performers as $performer): ?>
        <a class="artist-link" href="<?= $performer->id > 0 ? "/jazz/performer?id={$performer->id}" : "#" ?>">
          <div class="card-soft">

            <?php if (!empty($performer->image_path)): ?>
              <img
                src="<?= htmlspecialchars($performer->image_path) ?>"
                alt="<?= htmlspecialchars($performer->name) ?>"
                class="img-placeholder artist"
              >
            <?php else: ?>
              <div class="img-placeholder artist"></div>
            <?php endif; ?>

            <p class="artist-name"><?= htmlspecialchars($performer->name) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-muted">No performers found.</div>
    <?php endif; ?>
  </div>

  <p class="text-center mt-4 mb-2" style="font-family:'Playfair Display',serif;">
    Secure your spot at Haarlem Jazz 2026 —
    <a href="/tickets/jazz" style="color:var(--burgundy); font-weight:700;">Buy your tickets</a>
    today!
  </p>

  <div class="maps-wrap mt-3">
    <?php if (!empty($locations)): ?>
      <?php foreach ($locations as $location): ?>
        <div class="card-soft map-card">
          <div class="map-title"><?= htmlspecialchars($location->name) ?></div>

          <div class="map-frame">
            <iframe
              class="map-iframe"
              src="<?= htmlspecialchars($location->googleMapsEmbedUrl ?? '') ?>"
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

  <h3 class="mt-5" style="font-family:'Playfair Display',serif;">
    You might also like…
  </h3>

  <div class="row g-3 mt-1 mb-4">
    <?php if (!empty($recommendations)): ?>
      <?php foreach ($recommendations as $recommendation): ?>
        <div class="col-12 col-md-4">
          <a class="card-soft d-block h-100 text-decoration-none" href="<?= htmlspecialchars($recommendation->url ?? '#') ?>">

            <?php if (!empty($recommendation->imagePath)): ?>
              <img
                src="<?= htmlspecialchars($recommendation->imagePath) ?>"
                alt="<?= htmlspecialchars($recommendation->title ?: 'Recommendation') ?>"
                class="img-placeholder rec"
              >
            <?php else: ?>
              <div class="img-placeholder rec"></div>
            <?php endif; ?>

            <div class="p-3">
              <h4 style="font-family:'Playfair Display',serif; font-size:18px; margin:0 0 6px; color:var(--burgundy);">
                <?= htmlspecialchars($recommendation->title) ?>
              </h4>

              <p class="text-muted mb-0" style="font-size:12px;">
                <?= htmlspecialchars($recommendation->description) ?>
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