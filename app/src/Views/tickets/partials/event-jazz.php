<?
/** @var App\Models\Jazz\JazzPerformer $event */
?>

<article class="tickets-event" aria-label="<?= htmlspecialchars($event->name) ?>">
    <div class="tickets-event__time">
        <img class="ticekt-event__img" src="<?= $event->image_path ?>" alt="restaurant image">
    </div>
    <div class="tickets-event__info">
        <h4 class="tickets-event__name">
            <a href="<?= '/jazz/performer?id=' . $event->id ?>">
                <?= htmlspecialchars($event->name) ?>
            </a>
        </h4>
        <p class="tickets-event__meta">
            <?= htmlspecialchars($event->getDateTimeFormated()) ?>
            <span class="meta-sep">|</span> 
            <?= htmlspecialchars($event->bio) ?>
        </p>                                                                      
    </div>  
    <div class="tickets-event__action">
        <div class="tickets-event__form">
            <a href="<?= '/jazz/book?perf=' . $event->id ?>" class="tickets-btn tickets-btn--price">Reserve</a>
        </div>                                           
    </div>                                    
</article>