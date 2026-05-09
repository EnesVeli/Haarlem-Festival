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
            <a href="/tickets/history" class="tickets-tab <?= $view_model->categoryKey === 'history' ? 'tickets-tab--active' : '' ?>" aria-label="History tickets">History</a>
            <a href="/tickets/stories" class="tickets-tab <?= $view_model->categoryKey === 'stories' ? 'tickets-tab--active' : '' ?>" aria-label="Stories tickets">Stories</a>
            <a href="/tickets/yummy" class="tickets-tab <?= $view_model->categoryKey === 'yummy' ? 'tickets-tab--active' : '' ?>" aria-label="Yummy tickets">Yummy</a>
            <a href="/tickets/jazz" class="tickets-tab <?= $view_model->categoryKey === 'jazz' ? 'tickets-tab--active' : '' ?>" aria-label="Jazz tickets">Jazz</a>
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