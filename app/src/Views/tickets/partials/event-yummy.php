<?
/** @var App\Models\Restaurant $event */
?>

<article class="tickets-event" aria-label="<?= htmlspecialchars($event->name) ?>">
    <div class="tickets-event__time">
        <img class="ticekt-event__img" src="<?= '/assets/uploads/yummy/restaurants/' . $event->main_img_path ?>" alt="restaurant image">
    </div>
    <div class="tickets-event__info">
        <h4 class="tickets-event__name">
            <a href="<?= '/yummy/restaurant?id=' . $event->restaurant_id ?>">
                <?= htmlspecialchars($event->name) ?>
            </a>
        </h4>
        <p class="tickets-event__meta">
            <?= htmlspecialchars($event->getRatingFormated()) ?>
            <span class="meta-sep">|</span> <?= htmlspecialchars($event->mini_text) ?>
        </p>                                                                      
    </div>  
    <div class="tickets-event__action">
        <div class="tickets-event__form">
            <a href="<?= '/yummy/book?id=' . $event->restaurant_id ?>" class="tickets-btn tickets-btn--price">Book</a>
        </div>                                           
    </div>                                    
</article>