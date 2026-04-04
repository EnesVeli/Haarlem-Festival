<?php
/**
 * Shared non-stories tickets page.
 *
 * Variables available via extract($data):
 *   \App\ViewModels\TicketsCategoryViewModel $viewModel
 *   ?string                                  $success
 *   ?string                                  $error
 */

/** @var \App\ViewModels\TicketsCategoryViewModel $viewModel */
?>

<section class="tickets-page" aria-labelledby="tickets-heading">
    <div class="tickets-container">

        <header class="tickets-header">
            <h1 id="tickets-heading">Festival Program</h1>
            <p class="tickets-subtitle">Select a category to explore events and book your tickets.</p>
        </header>

        <nav class="tickets-tabs" aria-label="Event categories">
            <a href="/tickets/jazz" class="tickets-tab <?= $viewModel->categoryKey === 'jazz' ? 'tickets-tab--active' : '' ?>" aria-label="Haarlem Jazz tickets">Haarlem Jazz</a>
            <a href="/tickets/dance" class="tickets-tab <?= $viewModel->categoryKey === 'dance' ? 'tickets-tab--active' : '' ?>" aria-label="Dance tickets">Dance!</a>
            <a href="/tickets/yummy" class="tickets-tab <?= $viewModel->categoryKey === 'yummy' ? 'tickets-tab--active' : '' ?>" aria-label="Yummy tickets">Yummy</a>
            <a href="/tickets/history" class="tickets-tab <?= $viewModel->categoryKey === 'history' ? 'tickets-tab--active' : '' ?>" aria-label="History tickets">History</a>
            <a href="/tickets/stories" class="tickets-tab" aria-label="Stories tickets">Stories</a>
        </nav>

        <?php if (!empty($success)): ?>
            <div class="tickets-flash tickets-flash--success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="tickets-flash tickets-flash--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="tickets-content">
            <h2 class="tickets-content__title"><?= htmlspecialchars($viewModel->contentTitle) ?></h2>

            <?php if (empty($viewModel->eventsByDay)): ?>
                <p class="tickets-empty" role="alert"><?= htmlspecialchars($viewModel->emptyMessage) ?></p>
            <?php else: ?>

                <?php foreach ($viewModel->eventsByDay as $dayLabel => $dayEvents): ?>
                <section class="tickets-day" aria-labelledby="day-<?= htmlspecialchars(strtolower(str_replace([',', ' '], ['-', '-'], $dayLabel))) ?>">
                    <h3 class="tickets-day__heading" id="day-<?= htmlspecialchars(strtolower(str_replace([',', ' '], ['-', '-'], $dayLabel))) ?>">
                        <?= htmlspecialchars($dayLabel) ?>
                    </h3>

                    <div class="tickets-day__list">
                        <?php foreach ($dayEvents as $event): ?>
                        <?php
                            $timeRange = date('H:i', strtotime((string)$event['start_time']))
                                . ' - '
                                . date('H:i', strtotime((string)$event['end_time']));
                            $price = (float)($event['price'] ?? 0.0);
                            $available = (int)($event['available'] ?? 0);
                            $eventId = (int)($event['event_id'] ?? 0);
                            $ticketTypeId = (int)($event['ticket_type_id'] ?? 0);
                            $venueName = trim((string)($event['venue_name'] ?? ''));
                            $eventDescription = trim((string)($event['description'] ?? ''));
                        ?>
                        <article class="tickets-event" aria-label="<?= htmlspecialchars((string)$event['name']) ?>">

                            <div class="tickets-event__time">
                                <span><?= htmlspecialchars($timeRange) ?></span>
                            </div>

                            <div class="tickets-event__info">
                                <h4 class="tickets-event__name">
                                    <a href="<?= htmlspecialchars($viewModel->eventLink) ?>">
                                        <?= htmlspecialchars((string)$event['name']) ?>
                                    </a>
                                </h4>
                                <p class="tickets-event__meta">
                                    <?= htmlspecialchars($venueName) ?>
                                    <?php if ($eventDescription !== '' && strcasecmp($eventDescription, $venueName) !== 0): ?>
                                        <span class="meta-sep">|</span> <?= htmlspecialchars($eventDescription) ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="tickets-event__action">
                                <?php if ($available > 0 && $ticketTypeId > 0): ?>
                                    <form method="POST" action="/cart/add" class="tickets-event__form">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($viewModel->csrfToken) ?>">
                                        <input type="hidden" name="event_id" value="<?= $eventId ?>">
                                        <input type="hidden" name="ticket_type_id" value="<?= $ticketTypeId ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="redirect_back" value="/tickets/<?= htmlspecialchars($viewModel->categoryKey) ?>">
                                        <button type="submit"
                                                class="tickets-btn tickets-btn--price"
                                                aria-label="Add <?= htmlspecialchars((string)$event['name']) ?> to cart">
                                            <?= $price > 0 ? ('€' . number_format($price, 2)) : 'Free' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="tickets-btn tickets-btn--soldout" disabled>
                                        Sold out
                                    </button>
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
