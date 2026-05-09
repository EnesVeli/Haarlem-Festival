<?php
/** @var \App\ViewModels\TicketsCategoryViewModel $view_model */
/** @var ?string $error_message */
?>

<section class="tickets-page" aria-labelledby="tickets-heading">
    <div class="tickets-container">

        <header class="tickets-header">
            <h1 id="tickets-heading">Festival Tickets</h1>
            <p class="tickets-subtitle">Select a category to explore events and book your tickets.</p>
        </header>

        <nav class="tickets-tabs" aria-label="Event categories">
            <a href="/tickets/jazz" class="tickets-tab <?= $view_model->categoryKey === 'jazz' ? 'tickets-tab--active' : '' ?>" aria-label="Haarlem Jazz tickets">History</a>
            <a href="/tickets/stories" class="tickets-tab <?= $view_model->categoryKey === 'stories' ? 'tickets-tab--active' : '' ?>" aria-label="Dance tickets">Stories</a>
            <a href="/tickets/yummy" class="tickets-tab <?= $view_model->categoryKey === 'yummy' ? 'tickets-tab--active' : '' ?>" aria-label="Yummy tickets">Yummy</a>
            <a href="/tickets/history" class="tickets-tab <?= $view_model->categoryKey === 'history' ? 'tickets-tab--active' : '' ?>" aria-label="History tickets">Jazz</a>
        </nav>

        <?php if (!empty($error_message)): ?>
            <div class="tickets-flash tickets-flash--error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <div class="tickets-content">
            <h2 class="tickets-content__title"><?= htmlspecialchars($view_model->contentTitle) ?></h2>

            <?php if (empty($view_model->events)): ?>
                <p class="tickets-empty" role="alert"><?= htmlspecialchars($view_model->emptyMessage) ?></p>
            <?php else: ?>
                <div class="tickets-day__list"></div>
                    <? foreach ($view_model->events as $event): ?>                    
                        <?php include '/app/src/Views/tickets/partials/event-' . $view_model->categoryKey . '.php'; ?>             
                    <? endforeach; ?>

                    <?php include '/app/src/Views/tickets/partials/page-selector.php'; ?>   
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>

</script>

<!-- <? /*
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
                            <input type="hidden" name="redirect_back" value="/tickets/<?= htmlspecialchars($viewModel->categoryKey) ?>">
                            <div class="tickets-qty-stepper">
                                <button type="button" class="tickets-qty-btn tickets-qty-btn--minus" aria-label="Decrease quantity">−</button>
                                <input type="number" name="quantity" value="1" min="1" max="<?= $available ?>" class="tickets-qty-input" aria-label="Quantity">
                                <button type="button" class="tickets-qty-btn tickets-qty-btn--plus" aria-label="Increase quantity">+</button>
                            </div>
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

*/ ?> -->