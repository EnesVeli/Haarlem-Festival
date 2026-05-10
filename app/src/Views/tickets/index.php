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
        <?php include '/app/src/Views/tickets/partials/tabs.php'; ?>   

        <div class="tickets-placeholder">
            <p>Please select an event category above to view available tickets.</p>
        </div>
    </div>
</section>
