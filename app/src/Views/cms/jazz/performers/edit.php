<?php

$pageTitle = 'Edit Performer';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;
$performer = $vm->performer ?? null;
$highlights = $vm->highlights ?? [];
$tracks = $vm->tracks ?? [];

require __DIR__ . '/../../../partials/header.php';
?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Edit Performer';
$subtitle = 'Update performer information.';
$buttonText = 'Back to List';
$buttonLink = '/cms/jazz/performers';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'performers';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">
<div class="jazz-cms-welcome-card">

<h2 class="jazz-cms-section-title">Edit Performer</h2>

<form action="/cms/jazz/performers/update" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

<input type="hidden" name="id" value="<?= (int)($performer->id ?? 0) ?>">

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Name</label>
    <input
        type="text"
        name="name"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->name ?? '') ?>"
        required
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Performance Style</label>
    <input
        type="text"
        name="performance_style"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->performanceStyle ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Bio</label>
    <textarea name="bio" class="jazz-cms-textarea" rows="5"><?= htmlspecialchars($performer->bio ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Event Date</label>
    <input
        type="text"
        name="event_date_text"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->eventDateText ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Event Time</label>
    <input
        type="text"
        name="event_time_text"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->eventTimeText ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Venue Name</label>
    <input
        type="text"
        name="venue_name"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->venueName ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Venue Address</label>
    <input
        type="text"
        name="venue_address"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->venueAddress ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Price</label>
    <input
        type="text"
        name="price_text"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->priceText ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Note</label>
    <textarea name="note_text" class="jazz-cms-textarea" rows="3"><?= htmlspecialchars($performer->noteText ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Audio URL</label>
    <input
        type="text"
        name="audio_url"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->audioUrl ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Sort Order</label>
    <input
        type="number"
        name="sort_order"
        class="jazz-cms-input"
        value="<?= (int)($performer->sortOrder ?? 0) ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Active</label>
    <select name="is_active" class="jazz-cms-input">
        <option value="1" <?= ((int)($performer->isActive ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
        <option value="0" <?= ((int)($performer->isActive ?? 0) === 0) ? 'selected' : '' ?>>No</option>
    </select>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Performer Image</label>

    <div class="jazz-cms-upload-box" id="performerUploadBox">
        <input
            type="file"
            name="performer_image"
            id="performerImageInput"
            class="jazz-cms-file-input"
            accept="image/*"
        >
        <div class="jazz-cms-upload-inner">
            <strong>Drop image here</strong>
            <span>or choose a file</span>
        </div>
    </div>

    <small class="jazz-cms-help-text">
        Used for performer cards and performer content sections.<br>
        Upload path: <code>/assets/uploads/jazz/performers/</code>
    </small>

    <?php if (!empty($performer->imagePath)): ?>
        <div class="jazz-cms-image-preview-wrap">
            <p class="jazz-cms-preview-label">Current Performer Image</p>
            <img
                src="<?= htmlspecialchars($performer->imagePath) ?>"
                class="jazz-cms-image-preview"
                alt="Performer image"
            >
        </div>
    <?php endif; ?>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Hero Image</label>

    <div class="jazz-cms-upload-box" id="performerHeroUploadBox">
        <input
            type="file"
            name="performer_hero_image"
            id="performerHeroImageInput"
            class="jazz-cms-file-input"
            accept="image/*"
        >
        <div class="jazz-cms-upload-inner">
            <strong>Drop hero image here</strong>
            <span>or choose a file</span>
        </div>
    </div>

    <small class="jazz-cms-help-text">
        Used for the large top banner on the performer detail page.<br>
        Upload path: <code>/assets/uploads/jazz/performers/</code>
    </small>

    <?php if (!empty($performer->heroImagePath)): ?>
        <div class="jazz-cms-image-preview-wrap">
            <p class="jazz-cms-preview-label">Current Hero Image</p>
            <img
                src="<?= htmlspecialchars($performer->heroImagePath) ?>"
                class="jazz-cms-image-preview"
                alt="Hero image"
            >
        </div>
    <?php endif; ?>
</div>

<hr class="my-4">

<h3 class="jazz-cms-section-title">Career Highlights</h3>

<?php for ($i = 0; $i < 3; $i++): ?>
    <?php $highlight = $highlights[$i] ?? null; ?>

    <div class="jazz-cms-welcome-card" style="margin-bottom:20px;">
        <input type="hidden" name="highlights[<?= $i ?>][id]" value="<?= (int)($highlight->id ?? 0) ?>">

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Highlight Title</label>
            <input
                type="text"
                name="highlights[<?= $i ?>][title]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($highlight->title ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Description</label>
            <textarea
                name="highlights[<?= $i ?>][description]"
                class="jazz-cms-textarea"
                rows="3"
            ><?= htmlspecialchars($highlight->description ?? '') ?></textarea>
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Sort Order</label>
            <input
                type="number"
                name="highlights[<?= $i ?>][sort_order]"
                class="jazz-cms-input"
                value="<?= (int)($highlight->sortOrder ?? $i) ?>"
            >
        </div>
    </div>
<?php endfor; ?>

<hr class="my-4">

<h3 class="jazz-cms-section-title">Famous Tracks / Albums</h3>

<?php for ($i = 0; $i < 3; $i++): ?>
    <?php $track = $tracks[$i] ?? null; ?>

    <div class="jazz-cms-welcome-card" style="margin-bottom:20px;">
        <input type="hidden" name="tracks[<?= $i ?>][id]" value="<?= (int)($track->id ?? 0) ?>">

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Title</label>
            <input
                type="text"
                name="tracks[<?= $i ?>][title]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->title ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Release Date</label>
            <input
                type="text"
                name="tracks[<?= $i ?>][release_date_text]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->releaseDateText ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Description</label>
            <textarea
                name="tracks[<?= $i ?>][description]"
                class="jazz-cms-textarea"
                rows="3"
            ><?= htmlspecialchars($track->description ?? '') ?></textarea>
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Listen URL</label>
            <input
                type="text"
                name="tracks[<?= $i ?>][listen_url]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->listenUrl ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Image Path</label>
            <input
                type="text"
                name="tracks[<?= $i ?>][image_path]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->imagePath ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Sort Order</label>
            <input
                type="number"
                name="tracks[<?= $i ?>][sort_order]"
                class="jazz-cms-input"
                value="<?= (int)($track->sortOrder ?? $i) ?>"
            >
        </div>
    </div>
<?php endfor; ?>

<div class="jazz-cms-form-actions">
    <a href="/cms/jazz/performers" class="jazz-cms-btn jazz-cms-btn-outline">
        Cancel
    </a>

    <button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">
        Save Changes
    </button>
</div>

</form>

</div>
</div>
</div>
</div>

<script>
const performerUploadBox = document.getElementById('performerUploadBox');
const performerImageInput = document.getElementById('performerImageInput');

if (performerUploadBox && performerImageInput) {
    performerUploadBox.addEventListener('click', () => performerImageInput.click());

    performerUploadBox.addEventListener('dragover', e => {
        e.preventDefault();
        performerUploadBox.classList.add('is-dragover');
    });

    performerUploadBox.addEventListener('dragleave', () => {
        performerUploadBox.classList.remove('is-dragover');
    });

    performerUploadBox.addEventListener('drop', e => {
        e.preventDefault();
        performerUploadBox.classList.remove('is-dragover');

        if (e.dataTransfer.files.length > 0) {
            performerImageInput.files = e.dataTransfer.files;
        }
    });
}

const performerHeroUploadBox = document.getElementById('performerHeroUploadBox');
const performerHeroImageInput = document.getElementById('performerHeroImageInput');

if (performerHeroUploadBox && performerHeroImageInput) {
    performerHeroUploadBox.addEventListener('click', () => performerHeroImageInput.click());

    performerHeroUploadBox.addEventListener('dragover', e => {
        e.preventDefault();
        performerHeroUploadBox.classList.add('is-dragover');
    });

    performerHeroUploadBox.addEventListener('dragleave', () => {
        performerHeroUploadBox.classList.remove('is-dragover');
    });

    performerHeroUploadBox.addEventListener('drop', e => {
        e.preventDefault();
        performerHeroUploadBox.classList.remove('is-dragover');

        if (e.dataTransfer.files.length > 0) {
            performerHeroImageInput.files = e.dataTransfer.files;
        }
    });
}
</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>