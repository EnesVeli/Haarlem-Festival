<?php
/**
 * Partial view for "You might also like..." section
 * Used across detail pages to encourage cross-selling
 */
?>
<section class="stories-also-like">
    <h2>You might also like...</h2>
    <div class="stories-also-like__grid">
        <a href="/history" class="stories-also-like__card">
            <div class="stories-also-like__image stories-also-like__image--history" role="img" aria-label="Stroll Through History Event Image">
                <span class="stories-badge stories-badge--overlay">All Ages</span>
            </div>
            <h3>Stroll Through History</h3>
        </a>
        <a href="/jazz" class="stories-also-like__card">
            <div class="stories-also-like__image stories-also-like__image--jazz" role="img" aria-label="Jazz Event Image">
                <span class="stories-badge stories-badge--overlay">18+</span>
            </div>
            <h3>Jazz</h3>
        </a>
        <a href="/yummy" class="stories-also-like__card">
            <div class="stories-also-like__image stories-also-like__image--yummy" role="img" aria-label="Yummy Kids Menu Image">
                <span class="stories-badge stories-badge--overlay">Family</span>
            </div>
            <h3>Yummy! Kids Menu</h3>
        </a>
    </div>
</section>