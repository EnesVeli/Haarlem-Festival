<?php
$eventsByDay = [
    'Thursday' => [],
    'Friday' => [],
    'Saturday' => [],
    'Sunday' => []
];

$typeLabels = [
    'stories for the whole family' => 'Stories for the whole family',
    'recording podcast with audience' => 'Recording podcast with audience',
    'stories with impact' => 'Stories with impact',
    'best of' => 'Best of'
];

foreach ($events as $event) {
    $day = date('l', strtotime($event->start_time));

    if (isset($eventsByDay[$day])) {
        $eventsByDay[$day][] = $event;
    }
}
?>

<main class="stories-page">
    <section class="stories-hero-section">
        <div class="stories-container">
            <div class="stories-hero-card">
                <div class="stories-hero-text">
                    <h1><?= htmlspecialchars($pageTitle) ?></h1>
                    <p class="stories-hero-description">
                        <?= htmlspecialchars($pageDescription) ?>
                    </p>
                    <a href="#program" class="stories-primary-button">View program</a>
                    <p class="stories-hero-subtitle">
                        <?= htmlspecialchars($pageSubtitle) ?>
                    </p>
                </div>

                <div class="stories-hero-image"></div>
            </div>
        </div>
    </section>

    <section class="stories-quote-section">
        <div class="stories-container">
            <p class="stories-quote-text">Every street has a sound. Every building has a memory</p>
        </div>
    </section>

    <section class="stories-ticket-section">
        <div class="stories-container">
            <h2 class="stories-section-title stories-section-title--left">Info About Tickets</h2>

            <div class="stories-ticket-grid">
                <article class="stories-ticket-card">
                    <h3>Pay as you like</h3>
                    <p>
                        Some activities are priced <strong>pay as you like</strong>. We aim to keep these
                        events as accessible as possible so that everyone has the opportunity to participate.
                        We encourage visitors to donate based on how they valued the experience.
                    </p>
                    <p><em>A reservation is required to guarantee entry.</em></p>
                </article>

                <article class="stories-ticket-card">
                    <h3>HaarlemPas discount</h3>
                    <p>
                        People with the <strong>HaarlemPas</strong> receive a 25% discount on entry fees
                        for all stories in events with a fixed ticket price.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="stories-filter-section" id="program">
        <div class="stories-container">
            <div class="stories-filter-box">
                <h2 class="stories-section-title stories-section-title--left">Find your story</h2>

                <div class="stories-filter-row">
                    <div class="stories-filter-group">
                        <span class="stories-filter-label">DAY</span>
                        <div class="stories-day-buttons">
                            <button class="stories-day-button is-active" data-day="All" type="button">All</button>
                            <button class="stories-day-button" data-day="Thursday" type="button">Thu</button>
                            <button class="stories-day-button" data-day="Friday" type="button">Fri</button>
                            <button class="stories-day-button" data-day="Saturday" type="button">Sat</button>
                            <button class="stories-day-button" data-day="Sunday" type="button">Sun</button>
                        </div>
                    </div>

                    <div class="stories-filter-group">
                        <label class="stories-filter-label" for="filter-age">AGE GROUP</label>
                        <select id="filter-age" class="stories-filter-select">
                            <option value="">All ages</option>
                            <option value="2-102">2-102</option>
                            <option value="4+">4+</option>
                            <option value="10+">10+</option>
                            <option value="12+">12+</option>
                            <option value="16+">16+</option>
                        </select>
                    </div>

                    <div class="stories-filter-group">
                        <label class="stories-filter-label" for="filter-lang">LANGUAGE</label>
                        <select id="filter-lang" class="stories-filter-select">
                            <option value="">Any language</option>
                            <option value="NL">NL</option>
                            <option value="ENG">ENG</option>
                        </select>
                    </div>

                    <div class="stories-filter-group">
                        <label class="stories-filter-label" for="filter-type">TYPE</label>
                        <select id="filter-type" class="stories-filter-select">
                            <option value="">All types</option>
                            <option value="stories for the whole family">Stories for the whole family</option>
                            <option value="recording podcast with audience">Recording podcast with audience</option>
                            <option value="stories with impact">Stories with impact</option>
                            <option value="best of">Best of</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stories-program-section">
        <div class="stories-container">
            <h2 class="stories-section-title">Program - Last weekend of July</h2>

            <?php foreach ($eventsByDay as $dayName => $dayEvents): ?>
                <?php if (empty($dayEvents)) continue; ?>

                <div class="stories-day-section" data-day="<?= htmlspecialchars($dayName) ?>">
                    <h3 class="stories-day-title"><?= htmlspecialchars($dayName) ?></h3>

                    <div class="stories-card-grid">
                        <?php foreach ($dayEvents as $event): ?>
                            <?php
                            $typeText = $typeLabels[$event->story_type] ?? ucfirst($event->story_type);
                            $imagePath = !empty($event->image_path)
                                ? $event->image_path
                                : '/assets/images/stories/venue-placeholder.jpg';
                            ?>

                            <article
                                class="stories-event-card"
                                data-age="<?= htmlspecialchars($event->age_group) ?>"
                                data-lang="<?= htmlspecialchars($event->language) ?>"
                                data-type="<?= htmlspecialchars($event->story_type) ?>"
                            >
                                <div class="stories-event-card__content">
                                    <h4><?= htmlspecialchars($event->name) ?></h4>

                                    <div class="stories-badges">
                                        <span class="stories-badge stories-badge--type"><?= htmlspecialchars($typeText) ?></span>
                                        <span class="stories-badge"><?= htmlspecialchars($event->age_group) ?></span>
                                        <span class="stories-badge"><?= htmlspecialchars($event->language) ?></span>
                                    </div>

                                    <p class="stories-event-meta">
                                        <?= htmlspecialchars($event->venue_name) ?>
                                        <span>|</span>
                                        <?= date('H:i', strtotime($event->start_time)) ?>-<?= date('H:i', strtotime($event->end_time)) ?>
                                    </p>

                                    <a href="/stories/<?= htmlspecialchars($event->slug) ?>" class="stories-card-button">
                                        More about <?= htmlspecialchars($event->name) ?>
                                    </a>
                                </div>

                                <div
                                    class="stories-event-card__image"
                                    style="background-image: url('<?= htmlspecialchars($imagePath) ?>');"
                                ></div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="stories-map-section">
        <div class="stories-container">
            <div class="stories-map-box">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m40!1m8!1m3!1d9775.0!2d4.6355!3d52.3873!3m2!1i1024!2i768!4f13.1!4m29!3e2!4m5!1s0x47c5ef6c0619a665%3A0x7f0eb55e8f37e895!2sVerhalenhuis+Haarlem!3m2!1d52.390118!2d4.639054!4m5!1s0x47c5ef5e14e98db7%3A0x5e4c64e76f5ee6c7!2sDe+Schuur%2C+Lange+Begijnestraat+9%2C+Haarlem!3m2!1d52.3817!2d4.6338!4m5!1s0x47c5f11cc0a4e351%3A0x4b71f78a7e30a4d2!2sKweekcafe%2C+Kleverlaan+9%2C+Haarlem!3m2!1d52.3954!2d4.6441!4m5!1s0x47c5ef5dfc5e5db9%3A0x1a2e5f874d9bfe8b!2sCorrie+ten+Boom+huis!3m2!1d52.3823!2d4.6336!4m5!1s0x47c5f03af20ed82b%3A0x9b4b4b4b4b4b4b4b!2sTheater+Elswout!3m2!1d52.3697!2d4.6182!5e0!3m2!1sen!2snl!4v1700000000000"
                    width="100%"
                    height="360"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    title="Stories in Haarlem map"
                ></iframe>
            </div>
        </div>
    </section>

    <section class="stories-cta-section">
        <div class="stories-container">
            <div class="stories-cta-box">
                <div class="stories-cta-text">
                    <h2>Ready to plan your festival weekend?</h2>
                    <p>
                        Combine Stories in Haarlem with other festival events across the city
                        and build your perfect weekend program.
                    </p>
                </div>

                <div class="stories-cta-buttons">
                    <a href="/tickets" class="stories-cta-button stories-cta-button--dark">Book tickets</a>
                    <a href="/" class="stories-cta-button stories-cta-button--red">Back to home</a>
                </div>
            </div>
        </div>
    </section>

    <section class="stories-journey-section">
        <div class="stories-container">
            <h2 class="stories-section-title stories-section-title--left">Complete Your Journey</h2>

            <div class="stories-journey-grid">
                <a href="/history" class="stories-journey-card">
                    <div class="stories-journey-card__image stories-journey-card__image--history"></div>
                    <div class="stories-journey-card__content">
                        <h3>A Stroll Through History</h3>
                        <p>
                            Guided walking tour through historic Haarlem with local storytellers
                            sharing tales of the city's rich past.
                        </p>
                    </div>
                </a>

                <a href="/jazz" class="stories-journey-card">
                    <div class="stories-journey-card__image stories-journey-card__image--jazz"></div>
                    <div class="stories-journey-card__content">
                        <h3>Jazz</h3>
                        <p>
                            Interactive magic and illusion show at the famous Teylers Museum,
                            perfect for families and wonder-seekers.
                        </p>
                    </div>
                </a>

                <a href="/yummy" class="stories-journey-card">
                    <div class="stories-journey-card__image stories-journey-card__image--yummy"></div>
                    <div class="stories-journey-card__content">
                        <h3>Yummy!</h3>
                        <p>
                            Culinary storytelling experience with local chefs and food historians
                            exploring Dutch cuisine traditions.
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </section>
</main>

<script>
    const dayButtons = document.querySelectorAll('.stories-day-button');
    const ageSelect = document.getElementById('filter-age');
    const langSelect = document.getElementById('filter-lang');
    const typeSelect = document.getElementById('filter-type');

    let activeDay = 'All';

    function applyFilters() {
        document.querySelectorAll('.stories-day-section').forEach(section => {
            const showDay = activeDay === 'All' || section.dataset.day === activeDay;
            section.style.display = showDay ? '' : 'none';
        });

        document.querySelectorAll('.stories-event-card').forEach(card => {
            const ageOk = ageSelect.value === '' || card.dataset.age === ageSelect.value;
            const langOk = langSelect.value === '' || card.dataset.lang === langSelect.value;
            const typeOk = typeSelect.value === '' || card.dataset.type === typeSelect.value;

            card.style.display = ageOk && langOk && typeOk ? '' : 'none';
        });
    }

    dayButtons.forEach(button => {
        button.addEventListener('click', function () {
            dayButtons.forEach(item => item.classList.remove('is-active'));
            this.classList.add('is-active');
            activeDay = this.dataset.day;
            applyFilters();
        });
    });

    ageSelect.addEventListener('change', applyFilters);
    langSelect.addEventListener('change', applyFilters);
    typeSelect.addEventListener('change', applyFilters);
</script>
