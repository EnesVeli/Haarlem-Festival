<?
/** @var \App\ViewModels\TicketsHistoryCategoryViewModel $view_model */
/** @var App\Models\History\HistoryTimeSlot $slot */
/** @var int $i */ // - date offset
?>

<article class="tickets-event" aria-label="<?= 'history guided tour' ?>">
    <div class="tickets-event__time">
        <img class="ticekt-event__img" src="<?= '/assets/uploads/history/bavo-church.jpg' ?>" alt="restaurant image">
    </div>
    <div class="tickets-event__info">
        <h4 class="tickets-event__name">
            <a href="/history">History Guided Tour</a>
        </h4>
        <p class="tickets-event__meta">
            <?= $slot->time->format('H:i') ?>
            <span class="meta-sep">|</span> 
            Guided tours available for a deeper experience.
        </p>                                                                      
    </div>  
    <div class="tickets-event__action">
        <div class="tickets-event__form">
            <a href="<?= '/history/booking?slot=' . $slot->slot_id . '&offset=' . $i ?>" class="tickets-btn tickets-btn--price">Book</a>
        </div>                                           
    </div>                                    
</article>