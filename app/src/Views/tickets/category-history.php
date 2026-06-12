<?php
/** @var \App\ViewModels\TicketsHistoryCategoryViewModel $view_model */
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

            <?php if (empty($view_model->time_slots)): ?>
                <p class="tickets-empty" role="alert"><?= htmlspecialchars($view_model->emptyMessage) ?></p>
            <?php else: ?>
                <? for($i = $view_model->first_day_offset; $i < $view_model->last_day_offset; $i++): ?>
                    <section class="tickets-day">
                        <h3 class="tickets-day__heading"><?= $view_model->getDateStringFromOffset($i) ?></h3>

                        <div class="tickets-day__list">
                            <? foreach ($view_model->time_slots as $slot): ?>                    
                                <?php include '/app/src/Views/tickets/partials/event-history.php'; ?>             
                            <? endforeach; ?>
                        </div>
                    </section>
                <? endfor; ?>

                <?php include '/app/src/Views/tickets/partials/page-selector.php'; ?>   
            <?php endif; ?>
        </div>

    </div>
</section>