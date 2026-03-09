</body>
<?php
// app/src/Views/partials/footer.php
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link href="/assets/partials/footer.css" rel="stylesheet">
    
<!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Company Info -->
                <div class="footer-column">
                    <div class="footer-logo">
                        <span>The Festival</span>
                    </div>
                    <p class="footer-info">
                        <i class="bi bi-calendar3"></i>
                        Week 30, Thursday - Sunday
                    </p>
                    <p class="footer-info">
                        <i class="bi bi-geo-alt"></i>
                        Haarlem, Netherlands
                    </p>
                    <p class="footer-contact">
                        <i class="bi bi-telephone"></i>
                        +31 (0)23 123 4567
                    </p>
                    <p class="footer-contact">
                        <i class="bi bi-envelope"></i>
                        info@thefestival.nl
                    </p>
                </div>
                
                <!-- Events -->
                <div class="footer-column">
                    <h6 class="footer-heading">Events</h6>
                    <ul class="footer-links">
                        <li><a href="/jazz">Haarlem Jazz</a></li>
                        <li><a href="/dance">DANCE!</a></li>
                        <li><a href="/yummy">Yummy!</a></li>
                        <li><a href="/history">A Stroll through History</a></li>
                        <li><a href="/stories">Stories in Haarlem</a></li>
                    </ul>
                </div>
                
                <!-- Visitor Info -->
                <div class="footer-column">
                    <h6 class="footer-heading">Visitor Info</h6>
                    <ul class="footer-links">
                        <li><a href="/getting-there">Getting There</a></li>
                        <li><a href="/parking">Parking</a></li>
                        <li><a href="/accessibility">Accessibility</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div class="footer-column">
                    <h6 class="footer-heading">Legal</h6>
                    <ul class="footer-links">
                        <li><a href="/terms">Terms & Privacy</a></li>
                    </ul>
                    
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 The Festival Haarlem. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.event-card, .step-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
    
</body>
</html>