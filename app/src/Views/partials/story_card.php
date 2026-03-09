<?php
/**
 * @var \App\Models\StoryEvent $event
 */
?>
<div class="ticket-card">
    <div class="card-image">
        <img src="<?= htmlspecialchars($event->image_path ?? '/assets/css/uploads/stories/default.png') ?>" alt="Event Image">
    </div>
    <div class="card-info">
        <div class="card-tags">
            <span class="tag"><?= htmlspecialchars($event->type ?? 'General') ?></span>
            <span class="tag"><?= htmlspecialchars($event->age_group ?? 'All Ages') ?></span>
            <span class="tag"><?= htmlspecialchars($event->language ?? 'NL') ?></span>
        </div>
        
        <h3 class="card-title"><?= htmlspecialchars($event->title) ?></h3>
        <p class="card-location"><?= htmlspecialchars($event->location) ?></p>
        
        <div class="card-meta">
            <span><?= date('H:i', strtotime($event->start_time)) ?> - <?= $event->end_time ? date('H:i', strtotime($event->end_time)) : '' ?></span>
        </div>
        
        <a href="/stories/detail?id=<?= $event->id ?>" class="btn-more">More about <?= htmlspecialchars($event->title) ?></a>
    </div>
</div>