<?php
/** @var \App\ViewModels\HistoryIndexViewModel $viewModel */
$pageTitle = "History - Haarlem Festival";
$pageCSS = "history.css"; 
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
                        <div class="map-placeholder">
                            <p>Interactive Map - Route through Historic Haarlem</p>
                        </div>
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
                    
                    <div class="ticket-selector">
                        <label><strong>Select a Date:</strong></label>
                        <select class="date-select">
                            <option>Select</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                            <option>Sunday</option>
                        </select>
                        
                        <div class="available-slots-label">Available Time Slots (Thursday)</div>
                    </div>
                    
                    <div class="tickets-list">
                        <?php foreach ($viewModel->tickets as $ticket): ?>
                            <div class="ticket-row">
                                <div class="ticket-time"><?= htmlspecialchars($ticket['time_slot']) ?></div>
                                <div class="ticket-price">€<?= number_format($ticket['price'], 2) ?></div>
                                <div class="ticket-spots"><?= htmlspecialchars($ticket['available_spots']) ?> spots left</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="btn-book">Book</button>
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
            <div class="journey-card">
                <img src="/assets/uploads/History/stories-haarlem.jpg" alt="Stories in Haarlem">
                <div class="journey-body">
                    <h3>Stories in Haarlem</h3>
                    <p>Guided walking tour through Haarlem with local storytellers sharing tales of the city's rich past.</p>
                </div>
            </div>
            <div class="journey-card">
                <img src="/assets/uploads/History/jazz-event.jpg" alt="Jazz">
                <div class="journey-body">
                    <h3>Jazz</h3>
                    <p>Interactive magic and illusion show at the famous Teylers Museum, perfect for families and wonder-seekers.</p>
                </div>
            </div>
            <div class="journey-card">
                <img src="/assets/uploads/History/yummy-event.jpg" alt="Yummy">
                <div class="journey-body">
                    <h3>Yummy!</h3>
                    <p>Culinary storytelling experience with local chefs and food historians exploring Dutch cuisine traditions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>