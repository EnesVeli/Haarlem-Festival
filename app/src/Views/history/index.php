<?php
$pageTitle = "History - Haarlem Festival";
$pageCSS = "history.css"; 

/** @var \App\ViewModels\History\HistoryIndexViewModel $viewModel */
/** @var ?string $error_message */

require __DIR__ . '/../partials/header.php';
?>

<!-- HERO SECTION -->
<section class="history-hero" style="background-image: url('/assets/uploads/History/<?= htmlspecialchars($viewModel->heroImage()) ?>'); background-size: cover; background-position: center;">
    <div class="container">
        <div class="hero-content">
            <h1><?= htmlspecialchars($viewModel->heroTitle()) ?></h1>
            <p><?= htmlspecialchars($viewModel->heroSubtitle()) ?></p>
        </div>
    </div>
</section>

<!-- THE GOLDEN CITY OF THE NORTH -->
<section class="section-padding">
    <div class="container">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <div class="golden-city-grid">
            <div class="golden-city-text">
                <h2 class="section-title-burgundy"><?= htmlspecialchars($viewModel->introTitle()) ?></h2>
                <p><?= htmlspecialchars($viewModel->introSubtitle()) ?></p>
                <p>It is a city of resilience—surviving the great fire of 1576 and the Spanish Siege—and a city of beauty, inspiring masters like Frans Hals and Jacob van Ruisdael. Today, its cobblestone streets still echo with the footsteps of merchants, artists, and seekers who built this magnificent city.</p>
                
                <div class="icon-boxes">
                    <div class="icon-box">
                        <div class="icon-circle">🎨</div>
                        <h4>Art Center</h4>
                        <p>Home to the Dutch Golden Age painters</p>
                    </div>
                    <div class="icon-box">
                        <div class="icon-circle">🏛️</div>
                        <h4>Printing</h4>
                        <p>Birthplace of the printing press in Holland</p>
                    </div>
                    <div class="icon-box">
                        <div class="icon-circle">⚓</div>
                        <h4>Trade</h4>
                        <p>Gateway hub of the textile trade</p>
                    </div>
                </div>
            </div>
            
            <div class="golden-city-images">
                <img src="/assets/uploads/History/grote-markt.jpg" alt="Grote Markt" class="city-image">
                <img src="/assets/uploads/History/historic-buildings.jpg" alt="Historic Buildings" class="city-image">
            </div>
        </div>
    </div>
</section>

<!-- EXPERIENCE THE STORY -->
<section class="experience-section">
    <div class="container text-center">
        <h2 class="section-title-burgundy">Experience the Story Yourself</h2>
        <p class="experience-text">While reading about history is fascinating, walking through it is transformative. We invite you to this out "Stroll through History," a curated route that takes you past the most significant landmarks of our city.</p>
        <a href="#highlights" class="btn btn-navy">View Route Highlights</a>
    </div>
</section>

<!-- ROUTE HIGHLIGHTS WITH SIDEBAR -->
<section class="section-padding" id="highlights">
    <div class="container">
        <h2 class="section-title-burgundy mb-5">Route Highlights</h2>
        
        <div class="route-section-wrapper">
            <!-- LEFT SIDE: Highlights and Map -->
            <div class="route-main-content">
                <!-- Highlights Grid -->
                <div class="highlights-grid">
                    <?php foreach ($viewModel->highlights as $highlight): ?>
                        <div class="highlight-card">
                            <img src="/assets/uploads/History/<?= htmlspecialchars($highlight['image']) ?>" 
                                 alt="<?= htmlspecialchars($highlight['title']) ?>" 
                                 class="highlight-image">
                            <div class="highlight-body">
                                <h3><?= htmlspecialchars($highlight['title']) ?></h3>
                                <p><?= htmlspecialchars($highlight['description']) ?></p>
                                <?php if (!empty($highlight['slug'])): ?>
                                    <a href="/history/<?= htmlspecialchars($highlight['slug']) ?>" class="learn-more-link">Learn More →</a>
                                <?php else: ?>
                                    <a href="#" class="learn-more-link">Learn More →</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- WALKING ROUTE MAP -->
                <div class="map-section">
                    <h3>Walking Route Map</h3>
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m24!1m8!1m3!1d4892.354522174083!2d4.6374!3d52.3813!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x47c5ef8a08c20de5%3A0x6f0da2e6e5df6e90!2sGrote+Kerk+Haarlem!3m2!1d52.3813!2d4.6374!4m5!1s0x47c5ef8b5b0f5555%3A0x7e1fae8c8a52a68!2sTeylers+Museum%2C+Spaarne+16%2C+2011+CH+Haarlem!3m2!1d52.3795!2d4.6397!5e0!3m2!1sen!2snl!4v1700000000000"
                            width="100%" height="400" style="border:0;border-radius:8px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Sidebar -->
            <div class="route-sidebar">
                <!-- BETTER YOUR WALK -->
                <div class="better-walk-box">
                    <h3><?= htmlspecialchars($viewModel->walkTitle()) ?></h3>
                    <p><?= htmlspecialchars($viewModel->walkSubtitle()) ?></p>
                    
                    <?php if ($viewModel->hasWalkImage()): ?>
                        <img src="/assets/uploads/History/<?= htmlspecialchars($viewModel->walkImage()) ?>" alt="Walk guide">
                    <?php endif; ?>
                    
                    <div class="walk-features">
                        <span class="feature-badge">👥 Expert historian guides</span>
                        <span class="feature-badge">🏛️ Exclusive access to interiors</span>
                    </div>
                </div>

                <!-- GUIDED TOUR TICKETS -->
                <div class="tickets-box">
                    <h3>Guided Tour Tickets</h3>
                    <p class="subtitle">Secure your guide to history</p>
                    
                    <?php
                        $minDate = new DateTime();
                        $maxDate = (clone $minDate)->add(new DateInterval('P' . ($viewModel->max_date_offset - 1) . 'D'));
                    ?>
                    <div class="ticket-selector">
                        <label><strong>Select a Date:</strong></label>
                        <input
                            type="date"
                            class="date-select"
                            id="tour-date"
                            min="<?= $minDate->format('Y-m-d') ?>"
                            max="<?= $maxDate->format('Y-m-d') ?>"
                            data-min-date="<?= $minDate->format('Y-m-d') ?>"
                            onchange="updateDayLabel(this)"
                        >
                        <p class="date-help-text">Tours are available Thursday through Sunday.</p>
                        
                        <div class="available-slots-label" id="slots-day-label">Available Time Slots</div>
                    </div>
                    
                    <div class="tickets-list">
                        <?php foreach ($viewModel->time_slots as $time_slot): ?>
                            <div id="<?= 'time_slot_' . $time_slot->slot_id ?>" class="ticket-row" style="cursor:pointer" onclick="selectTicket(<?= $time_slot->slot_id ?>)">
                                <div class="ticket-time"><?= $time_slot->time->format('H:i') ?></div>
                                <!-- <div class="ticket-spots"> spots left</div> -->
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn-book" onclick="onBookButtonClick()">Book</button>

                    <script>
                        let date_offset = -1;
                        let slot_id = -1;

                        let selectedTicketId = null;
                        let selectedTime = '';

                        function updateDayLabel(input) {
                            if (!input.value) {
                                date_offset = -1;
                                document.getElementById('slots-day-label').textContent = 'Available Time Slots';
                                return;
                            }

                            const selectedDate = createLocalDate(input.value);
                            const minDate = createLocalDate(input.dataset.minDate);
                            const selectedDayNumber = selectedDate.getDay();

                            if (![0, 4, 5, 6].includes(selectedDayNumber)) {
                                alert('Guided tours can only be booked from Thursday to Sunday.');
                                input.value = '';
                                date_offset = -1;
                                document.getElementById('slots-day-label').textContent = 'Available Time Slots';
                                return;
                            }

                            date_offset = Math.round((selectedDate - minDate) / 86400000);

                            const selectedDay = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
                            const label = document.getElementById('slots-day-label');
                            label.textContent = selectedDay
                                ? 'Available Time Slots (' + selectedDay + ')'
                                : 'Available Time Slots';
                        }
                        function createLocalDate(value) {
                            const parts = value.split('-').map(Number);
                            return new Date(parts[0], parts[1] - 1, parts[2]);
                        }
                        function selectTicket(id) {
                            slot_id = id;

                            document.querySelectorAll('.ticket-row').forEach(r => r.style.background = '');
                            document.getElementById('time_slot_' + id).style.background = 'rgba(255,255,255,0.15)';
                        }
                        function onBookButtonClick() {
                            if (slot_id == -1) 
                            {
                                alert('Please select a time slot first.');
                                return;
                            }
                            if (date_offset == -1)
                            { 
                                alert('Please select a date first.'); 
                                return;
                            }

                            window.location.href = "/history/booking?slot=" + slot_id + '&offset=' + date_offset;
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="section-padding" <?php if ($viewModel->hasCtaImage()): ?> 
    style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/assets/uploads/History/<?= htmlspecialchars($viewModel->ctaImage()) ?>'); background-size: cover; background-position: center; color: white;"
<?php endif; ?>>
    <div class="container">
        <div class="cta-box" <?= $viewModel->hasCtaImage() ? 'style="background: transparent;"' : '' ?>>
            <h2><?= htmlspecialchars($viewModel->ctaTitle()) ?></h2>
            <p><?= htmlspecialchars($viewModel->ctaSubtitle()) ?></p>
            <div class="cta-buttons">
                <a href="/tickets" class="btn btn-orange">Book tickets</a>
                <a href="/" class="btn btn-outline-white">Back to home</a>
            </div>
        </div>
    </div>
</section>

<!-- COMPLETE YOUR JOURNEY -->
<section class="section-padding bg-light">
    <div class="container">
        <h2 class="section-title-burgundy mb-5">Complete Your Journey</h2>
        <div class="journey-grid">
            <a href="/stories" class="journey-card">
                <img src="/assets/uploads/History/stories-haarlem.jpg" alt="Stories in Haarlem">
                <div class="journey-body">
                    <h3>Stories in Haarlem</h3>
                    <p>Guided walking tour through Haarlem with local storytellers sharing tales of the city's rich past.</p>
                </div>
            </a>
            <a href="/jazz" class="journey-card">
                <img src="/assets/uploads/History/jazz-event.jpg" alt="Jazz">
                <div class="journey-body">
                    <h3>Jazz</h3>
                    <p>Interactive magic and illusion show at the famous Teylers Museum, perfect for families and wonder-seekers.</p>
                </div>
            </a>
            <a href="/yummy" class="journey-card">
                <img src="/assets/uploads/History/yummy-event.jpg" alt="Yummy">
                <div class="journey-body">
                    <h3>Yummy!</h3>
                    <p>Culinary storytelling experience with local chefs and food historians exploring Dutch cuisine traditions.</p>
                </div>
            </a>
            <a href="/tickets" class="journey-card">
                <img src="/assets/uploads/History/tickets-event.jpg" alt="Tickets">
                <div class="journey-body">
                    <h3>Tickets</h3>
                    <p>Browse every festival event and reserve the tickets you need for your personal Haarlem experience.</p>
                </div>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
