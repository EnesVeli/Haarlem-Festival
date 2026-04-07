<?php
/**
 * CMS View for Creating/Editing Story Events
 * @var \App\Models\StoryEvent|null $event
 * @var array $ticketTypes
 */
?>
<main class="cms-container container mt-4 mb-5">
    <h2><?= $event ? 'Edit' : 'Create' ?> Story Event</h2>
    <?php if (!empty($cms_error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($cms_error) ?></div>
    <?php endif; ?>

    <form action="/cms/stories/save" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <?php if ($event): ?>
        <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($event->image_path) ?>">
        <input type="hidden" name="existing_gallery_1" value="<?= htmlspecialchars($event->gallery_image_1 ?? '') ?>">
        <input type="hidden" name="existing_gallery_2" value="<?= htmlspecialchars($event->gallery_image_2 ?? '') ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name"
                value="<?= htmlspecialchars($event->name ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="slug">Slug (URL)</label>
            <input type="text" class="form-control" id="slug" name="slug"
                value="<?= htmlspecialchars($event->slug ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"
                rows="5"><?= htmlspecialchars($event->description ?? '') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="story_type">Type</label>
                <input type="text" class="form-control" id="story_type" name="story_type"
                    value="<?= htmlspecialchars($event->story_type ?? '') ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="age_group">Age Group</label>
                <input type="text" class="form-control" id="age_group" name="age_group"
                    value="<?= htmlspecialchars($event->age_group ?? '') ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="language">Language</label>
                <select id="language" name="language" class="form-control">
                    <option value="EN" <?= ($event->language ?? '') === 'EN' ? 'selected' : '' ?>>English</option>
                    <option value="NL" <?= ($event->language ?? '') === 'NL' ? 'selected' : '' ?>>Dutch</option>
                </select>
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="is_pay_as_you_like" id="payCheck" value="1"
                <?= ($event->is_pay_as_you_like ?? false) ? 'checked' : '' ?>>
            <label class="form-check-label" for="payCheck">Is Pay-As-You-Like?</label>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_time">Start Time</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                    value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($event->start_time ?? 'now'))) ?>"
                    required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_time">End Time</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                    value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($event->end_time ?? 'now'))) ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="max_tickets">Max Tickets</label>
                <input type="number" class="form-control" id="max_tickets" name="max_tickets"
                    value="<?= (int) ($event->max_tickets ?? 0) ?>" min="0" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="venue_id">Venue ID</label>
                <input type="number" class="form-control" id="venue_id" name="venue_id"
                    value="<?= (int) ($event->venue_id ?? 1) ?>" min="1" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="performer_name">Performer Name</label>
            <input type="text" class="form-control" id="performer_name" name="performer_name"
                value="<?= htmlspecialchars($event->performer_name ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="performer_bio">Performer Bio</label>
            <textarea class="form-control" id="performer_bio" name="performer_bio"
                rows="4"><?= htmlspecialchars($event->performer_bio ?? '') ?></textarea>
        </div>

        <h5 class="mt-4">Ticket Prices</h5>
        <?php foreach ($ticketTypes as $tt): ?>
        <div class="mb-3">
            <label class="form-label"><?= htmlspecialchars($tt['name']) ?></label>
            <?php if ($tt['is_pay_as_you_like']): ?>
            <span class="d-block text-muted">Pay as you like &mdash; no fixed price</span>
            <?php else: ?>
            <input type="number" step="0.01" min="0" class="form-control"
                name="ticket_prices[<?= (int)$tt['type_id'] ?>]"
                value="<?= number_format((float)$tt['price'], 2, '.', '') ?>">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <div class="mb-3">
            <label class="form-label" for="image">Image Upload</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php if (!empty($event->image_path)): ?>
            <small class="text-muted">Current image: <?= htmlspecialchars($event->image_path) ?></small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="gallery_image_1">Gallery Image 1</label>
            <input type="file" class="form-control" id="gallery_image_1" name="gallery_image_1" accept="image/*">
            <?php if (!empty($event->gallery_image_1)): ?>
            <small class="text-muted">Current image: <?= htmlspecialchars($event->gallery_image_1) ?></small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="gallery_image_2">Gallery Image 2</label>
            <input type="file" class="form-control" id="gallery_image_2" name="gallery_image_2" accept="image/*">
            <?php if (!empty($event->gallery_image_2)): ?>
            <small class="text-muted">Current image: <?= htmlspecialchars($event->gallery_image_2) ?></small>
            <?php endif; ?>
        </div>



        <div class="mb-3">
            <label class="form-label" for="audio_title">Audio Title</label>
            <input type="text" class="form-control" id="audio_title" name="audio_title"
                value="<?= htmlspecialchars($event->audio_title ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label" for="audio_transcript">Audio Transcript</label>
            <textarea class="form-control" id="audio_transcript" name="audio_transcript"
                rows="4"><?= htmlspecialchars($event->audio_transcript ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Event</button>
        <a href="/cms/stories" class="btn btn-secondary">Cancel</a>
    </form>
</main>
