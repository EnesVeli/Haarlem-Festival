<?php

$user = $user ?? null;
$events = $events ?? [];
$pageTitle = 'Home - The Festival Haarlem';

require __DIR__ . '/partials/header.php';
?>

<link href="/assets/css/home.css" rel="stylesheet">

<section class="hero-main">
    <div class="hero-content-wrapper">
        <div class="hero-text">
            <h1>The Festival</h1>
            <p class="hero-subtitle">5 Events • 4 Days • One Haarlem</p>
            <p class="hero-description">
                Discover the vibrant heart of Haarlem this July during The Festival, a unique four day celebration 
                that transforms our historic city into a stage for culture, music, and culinary excellence.
            </p>
            <div class="hero-buttons">
                <a href="#events" class="btn btn-primary-custom">Explore Events</a>
                <a href="/program" class="btn btn-outline-custom">Build My Program</a>
            </div>
        </div>
        <div class="hero-info">
            <div class="info-badge">
                <i class="bi bi-calendar3"></i>
                <span>Week 30 | Thursday - Sunday</span>
            </div>
            <div class="info-badge">
                <i class="bi bi-geo-alt"></i>
                <span>Haarlem, Netherlands</span>
            </div>
        </div>
    </div>
</section>

<section class="how-to-use">
    <div class="container">
        <h2 class="section-heading">What Is My Program?</h2>
        <p class="section-description">
            The program is a build your own festival tool, that allows you to build you own festival with activities you like. 
            It makes planning easier by having everything in one place. It allows you to buy and book all of the needed places in one website.
        </p>
        
        <div class="how-to-steps">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Explore Events</h3>
                <p>Browse through our six unique events and discover what interests you most. From jazz to history, there's something for everyone.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Build Your Program</h3>
                <p>Create your personal festival schedule. Add all kinds of events to your wish list.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Book And Enjoy</h3>
                <p>Reserve your tickets and make other reservations for the festival. Get ready for four unforgettable days in Haarlem!</p>
            </div>
        </div>
    </div>
</section>

<!-- FESTIVAL EVENTS SECTION -->
<section class="festival-events" id="events">
    <div class="container">
        <h2 class="section-heading">Festival Events</h2>
        <p class="section-description">
            Whether you are a history buff, a jazz enthusiast, or a foodie, you will find your perfect rhythm in our city.
        </p>
        
        <div class="events-grid">
            <!-- Haarlem Jazz -->
            <div class="event-card">
                <div class="event-category">Music</div>
                <div class="event-image-placeholder jazz-bg">
                    <i class="bi bi-music-note-beamed"></i>
                </div>
                <div class="event-content">
                    <h3>Haarlem Jazz</h3>
                    <p>Experience world-class jazz performances across multiple venues. From smooth classics to contemporary fusion.</p>
                    <p class="event-detail">From soft saxophone melodies to energetic jam nights, Haarlem Jazz mixes tradition, modern sound, and warm summer nights...</p>
                    <div class="event-venues">
                        <small><i class="bi bi-geo-alt"></i> Patronaat Haarlem, Grand Cafe Brinkman, New Vegas</small>
                    </div>
                    <a href="/jazz" class="btn btn-explore">Explore Jazz</a>
                </div>
            </div>
            
            <!-- Dance -->
            <div class="event-card">
                <div class="event-category">Music</div>
                <div class="event-image-placeholder dance-bg">
                    <i class="bi bi-disc"></i>
                </div>
                <div class="event-content">
                    <h3>Dance!</h3>
                    <p>Top DJs bring the energy with electrifying performances. Get ready to move to the best electronic beats.</p>
                    <p class="event-detail">Dance is the electronic music experience of The Festival: three nights filled with house, techno and trance across Haarlem and Bloemendaal.</p>
                    <div class="event-venues">
                        <small><i class="bi bi-geo-alt"></i> Various venues across Haarlem</small>
                    </div>
                    <a href="/dance" class="btn btn-explore">Explore Dance</a>
                </div>
            </div>
            
            <!-- Yummy -->
            <div class="event-card">
                <div class="event-category">Food</div>
                <div class="event-image-placeholder food-bg">
                    <i class="bi bi-cup-hot"></i>
                </div>
                <div class="event-content">
                    <h3>Yummy!</h3>
                    <p>Gourmet dining with a twist. Haarlem's finest restaurants present exclusive festival menus.</p>
                    <p class="event-detail">From fancy dining to a quick bite in one of the many restaurants, Haarlem has it all. The city is quite famous for its wide range of restaurants and bars...</p>
                    <div class="event-venues">
                        <small><i class="bi bi-geo-alt"></i> Ratatouille, Restaurant ML, Urban Frenchy Bistro, Restaurant Fris</small>
                    </div>
                    <a href="/food" class="btn btn-explore">Explore Yummy</a>
                </div>
            </div>
            
            <!-- A Stroll through History -->
            <div class="event-card">
                <div class="event-category">Culture</div>
                <div class="event-image-placeholder history-bg">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="event-content">
                    <h3>A Stroll through History</h3>
                    <p>Walk through centuries of Dutch heritage. Discover Haarlem's historic landmarks with expert guides.</p>
                    <p class="event-detail">Discover the city of painters, merchants, and hidden courtyards. Experience 775 years of history in one unforgettable walk.</p>
                    <div class="event-venues">
                        <small><i class="bi bi-geo-alt"></i> Grote Markt, Corrie ten Boom house</small>
                    </div>
                    <a href="/history" class="btn btn-explore">Explore History</a>
                </div>
            </div>
            
            <!-- Stories in Haarlem -->
            <div class="event-card">
                <div class="event-category">Culture</div>
                <div class="event-image-placeholder stories-bg">
                    <i class="bi bi-book"></i>
                </div>
                <div class="event-content">
                    <h3>Stories in Haarlem</h3>
                    <p>Immerse yourself in captivating narratives. From local legends to international storytellers.</p>
                    <p class="event-detail">During the last weekend of July, Stories in Haarlem brings live stories, podcasts and family shows to different locations across the city.</p>
                    <div class="event-venues">
                        <small><i class="bi bi-geo-alt"></i> Verhalenhuis Haarlem, Elswout Theater, De Schuur, Café de Roemer</small>
                    </div>
                    <a href="/stories" class="btn btn-explore">Explore Stories</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER SECTION -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-box">
            <h3>Stay Updated</h3>
            <p>Subscribe to our newsletter for the latest updates</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Your email" required>
                <button type="submit" class="btn btn-primary-custom">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>