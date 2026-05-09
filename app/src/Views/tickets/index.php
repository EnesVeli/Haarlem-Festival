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

        <header class="tickets-header">
            <h1 id="tickets-heading">Festival Tickets</h1>
            <p class="tickets-subtitle">Select a category to explore events and book your tickets.</p>
        </header>

        <nav class="tickets-tabs" aria-label="Event categories">    
            <a href="/tickets/history" class="tickets-tab" aria-label="History tickets">History</a>
            <a href="/tickets/stories" class="tickets-tab" aria-label="Stories tickets">Stories</a>
            <a href="/tickets/yummy" class="tickets-tab" aria-label="Yummy tickets">Yummy</a>
            <a href="/tickets/jazz" class="tickets-tab" aria-label="Haarlem Jazz tickets">Jazz</a>   
            <!--<a href="/tickets/dance" class="tickets-tab" aria-label="Dance tickets">Dance</a>-->
        </nav>

        <div class="tickets-placeholder">
            <p>Please select an event category above to view available tickets.</p>
        </div>
    </div>
</section>
