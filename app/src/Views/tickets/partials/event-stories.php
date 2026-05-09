<?
/** @var App\Models\StoryEvent $event */
?>

<article class="tickets-event" aria-label="<?= htmlspecialchars($event->name) ?>">
    <div class="tickets-event__time">
        <img class="ticekt-event__img" src="<?= $event->image_path ?>" alt="restaurant image">
    </div>
    <div class="tickets-event__info">
        <h4 class="tickets-event__name">
            <a href="<?= '/stories/' . $event->slug ?>">
                <?= htmlspecialchars($event->name) ?>
            </a>
        </h4>
        <p class="tickets-event__meta">
            <?= htmlspecialchars($event->age_group) ?>
            <span class="meta-sep">|</span> 
            <?= htmlspecialchars($event->language) ?>
            <span class="meta-sep">|</span> 
            <?= htmlspecialchars($event->description) ?>
        </p>                                                                      
    </div>  
    <div class="tickets-event__action">
        <div class="tickets-event__form">
            <a href="<?= '/stories/' . $event->slug . '/book' ?>" class="tickets-btn tickets-btn--price">Reserve</a>
        </div>                                           
    </div>                                    
</article>