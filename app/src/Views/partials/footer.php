<?php
// app/src/Views/partials/footer.php
?>
  </main>

  <link href="/assets/partials/footer.css" rel="stylesheet">

  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-column">
          <div class="footer-logo"><span>The Festival</span></div>
          <p class="footer-info"><i class="bi bi-calendar3"></i> Week 30, Thursday - Sunday</p>
          <p class="footer-info"><i class="bi bi-geo-alt"></i> Haarlem, Netherlands</p>
          <p class="footer-contact"><i class="bi bi-telephone"></i> +31 (0)23 123 4567</p>
          <p class="footer-contact"><i class="bi bi-envelope"></i> info@thefestival.nl</p>
        </div>

        <div class="footer-column">
          <h6 class="footer-heading">Events</h6>
          <ul class="footer-links">
            <li><a href="/jazz">Haarlem Jazz</a></li>
            <li><a href="/dance">DANCE!</a></li>
            <li><a href="/food">Yummy!</a></li>
            <li><a href="/history">A Stroll through History</a></li>
            <li><a href="/stories">Stories in Haarlem</a></li>
          </ul>
        </div>

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
        <p>&copy; 2026 The Festival Haarlem. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
      const navLinks = document.querySelector('.nav-links');
      navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
    });
  </script>
</body>
</html>