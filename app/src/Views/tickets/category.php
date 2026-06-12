<?php
/** @var \App\ViewModels\TicketsCategoryViewModel $view_model */
/** @var ?string $error_message */
?>

<section class="tickets-page" aria-labelledby="tickets-heading">
    <div class="tickets-container">
        <?php include '/app/src/Views/tickets/partials/tabs.php'; ?>   

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