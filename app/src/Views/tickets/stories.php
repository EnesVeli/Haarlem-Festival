<?php
/**
 * Stories Tickets page — lists story events grouped by day.
 *
 * Variables available via extract($data):
 *   TicketsStoriesViewModel $viewModel — events grouped by day, CSRF token
 *   string                  $pageTitle — browser tab title
 *   string                  $pageCSS   — page-specific stylesheet
 */

/** @var \App\ViewModels\TicketsStoriesViewModel $viewModel */
?>

<section class="tickets-page" aria-labelledby="tickets-heading">
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
            <a href="/tickets/stories" class="tickets-tab tickets-tab--active" aria-label="Stories tickets">Stories</a>
        </nav>

        <!-- ── Stories ticket listing ───────────────────────────────── -->
        <div class="tickets-content">
            <h2 class="tickets-content__title">Stories Tickets</h2>

            <?php if (empty($viewModel->eventsByDay)): ?>
                <p class="tickets-empty" role="alert">No story events found at this time.</p>
            <?php else: ?>

                <?php foreach ($viewModel->eventsByDay as $dayLabel => $dayEvents): ?>
                <section class="tickets-day" aria-labelledby="day-<?= htmlspecialchars(strtolower(explode(',', $dayLabel)[0])) ?>">
                    <h3 class="tickets-day__heading" id="day-<?= htmlspecialchars(strtolower(explode(',', $dayLabel)[0])) ?>">
                        <?= htmlspecialchars($dayLabel) ?>
                    </h3>

                    <div class="tickets-day__list">
                        <?php foreach ($dayEvents as $event): ?>
                        <?php
                            $timeRange = date('H:i', strtotime($event->start_time))
                                       . ' - '
                                       . date('H:i', strtotime($event->end_time));

                            $isPayAsYouLike = (bool) $event->is_pay_as_you_like;
                            $price          = $isPayAsYouLike ? 0.00 : (float) $event->price;
                            $typeLabel      = ucfirst($event->story_type ?? '');
                            $ageLabel       = $event->age_group ?? 'All ages';
                            $langLabel      = $event->language ?? '';
                        ?>
                        <article class="tickets-event" aria-label="<?= htmlspecialchars($event->name) ?>">

                            <!-- Time column -->
                            <div class="tickets-event__time">
                                <span><?= htmlspecialchars($timeRange) ?></span>
                            </div>

                            <!-- Info column -->
                            <div class="tickets-event__info">
                                <h4 class="tickets-event__name">
                                    <a href="/stories/<?= htmlspecialchars($event->slug) ?>">
                                        <?= htmlspecialchars($event->name) ?>
                                    </a>
                                </h4>
                                <p class="tickets-event__meta">
                                    <?= htmlspecialchars($event->venue_name ?? '') ?>
                                    <?php if (!empty($ageLabel)): ?>
                                        <span class="meta-sep">|</span> <?= htmlspecialchars($ageLabel) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($langLabel)): ?>
                                        <span class="meta-sep">|</span> <?= htmlspecialchars($langLabel) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($typeLabel)): ?>
                                        <span class="meta-sep">|</span> <?= htmlspecialchars($typeLabel) ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <!-- Price / Book link column -->
                            <div class="tickets-event__action">
                                <?php if ($isPayAsYouLike): ?>
                                    <a href="/stories/<?= htmlspecialchars($event->slug) ?>/book"
                                       class="tickets-btn tickets-btn--paylike"
                                       aria-label="Book <?= htmlspecialchars($event->name) ?> — pay as you like">
                                        Pay as you like
                                    </a>
                                <?php else: ?>
                                    <a href="/stories/<?= htmlspecialchars($event->slug) ?>/book"
                                       class="tickets-btn tickets-btn--price"
                                       aria-label="Book <?= htmlspecialchars($event->name) ?> for €<?= number_format($price, 2) ?>">
                                        €<?= number_format($price, 2) ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

    </div>
</section>
