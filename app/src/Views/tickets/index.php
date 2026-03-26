<?php
/**
 * Main Tickets landing page — tab navigation for all festival event types.
 *
 * Variables available via extract($data):
 *   string $pageTitle — browser tab title (set by header partial)
 *   string $pageCSS   — page-specific stylesheet (set by header partial)
 */
?>

<section class="tickets-page" aria-label="Festival program tickets">
    <div class="tickets-container">

        <!-- ── Page heading ─────────────────────────────────────────── -->
        <header class="tickets-header">
            <h1 id="tickets-heading">Festival Program</h1>
            <p class="tickets-subtitle">Select a category to explore events and book your tickets.</p>
        </header>

        <!-- ── Event-type tabs ──────────────────────────────────────── -->
        <nav class="tickets-tabs" aria-label="Event categories">
            <a href="/tickets" class="tickets-tab" aria-label="Haarlem Jazz tickets">Haarlem Jazz</a>
            <a href="/tickets" class="tickets-tab" aria-label="Dance tickets">Dance!</a>
            <a href="/tickets" class="tickets-tab" aria-label="Yummy tickets">Yummy</a>
            <a href="/tickets" class="tickets-tab" aria-label="History tickets">History</a>
            <a href="/tickets/stories" class="tickets-tab" aria-label="Stories tickets">Stories</a>
        </nav>

        <!-- ── Placeholder message ──────────────────────────────────── -->
        <div class="tickets-placeholder">
            <p>Please select an event category above to view available tickets.</p>
        </div>

    </div>
</section>
