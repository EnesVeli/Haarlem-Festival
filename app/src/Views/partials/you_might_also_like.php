<?php
/**
 * Partial view for "You might also like..." section.
 */
?>
<section class="stories-also-like">
    <h2>You might also like...</h2>

    <div class="stories-also-like__grid">
        <a href="/history" class="stories-also-like__card">
            <img src="/assets/images/stories/journey-history.jpg" alt="A Stroll Through History"
                class="stories-also-like__image">
            <div class="stories-also-like__body">
                <h3>A Stroll Through History</h3>
                <p>Guided walking tour through historic Haarlem city's rich past.</p>
            </div>
        </a>

        <a href="/jazz" class="stories-also-like__card">
            <img src="/assets/images/stories/journey-jazz.jpg" alt="Jazz event" class="stories-also-like__image">
            <div class="stories-also-like__body">
                <h3>Jazz</h3>
                <p>Interactive music and fusion show at the festival's iconic venues.</p>
            </div>
        </a>

        <a href="/yummy" class="stories-also-like__card">
            <img src="/assets/images/stories/journey-yummy.jpg" alt="Yummy event" class="stories-also-like__image">
            <div class="stories-also-like__body">
                <h3>Yummy!</h3>
                <p>Culinary storytelling experience with local chefs and food traditions.</p>
            </div>
        </a>
    </div>
</section>

<?php static $alsoLikeStylesPrinted = false; ?>
<?php if (!$alsoLikeStylesPrinted): ?>
<?php $alsoLikeStylesPrinted = true; ?>
<style>
.stories-also-like {
    padding: 2rem 0 2.6rem;
}
.stories-also-like h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    margin: 0 0 1.1rem;
}
.stories-also-like__grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.9rem;
}
.stories-also-like__card {
    display: block;
    text-decoration: none;
    color: inherit;
    border: 1px solid #ded8cf;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
}
.stories-also-like__image {
    width: 100%;
    height: 84px;
    object-fit: cover;
    display: block;
}
.stories-also-like__body {
    padding: 0.35rem 0.55rem 0.5rem;
}
.stories-also-like__body h3 {
    margin: 0 0 0.18rem;
    color: #9f1d1d;
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    line-height: 1.2;
    text-decoration: underline;
    text-underline-offset: 2px;
}
.stories-also-like__body p {
    margin: 0;
    color: #6b7280;
    font-size: 0.72rem;
    line-height: 1.35;
}
@media (max-width: 860px) {
    .stories-also-like__grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?php endif; ?>
