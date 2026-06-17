<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzPerformersCmsViewModel $vm */

$pageTitle = 'Edit Performer';
$pageCSS = 'jazz.css';
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
        value="<?= htmlspecialchars($performer->performance_style ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Bio</label>
    <textarea name="bio" class="jazz-cms-textarea" rows="5"><?= htmlspecialchars($performer->bio ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Event Date</label>
    <input
        type="date"
        name="date"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->date->format('Y-m-d') ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Event Start Time</label>
    <input
        type="time"
        name="start_time"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->start_time->format('H:i') ?? '') ?>"
    >

    <label class="jazz-cms-label">Event End Time</label>
    <input
        type="time"
        name="end_time"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->end_time->format('H:i') ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Venue Name</label>
    <input
        type="text"
        name="venue_name"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->venue_name ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Venue Address</label>
    <input
        type="text"
        name="venue_address"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->venue_address ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Price</label>
    <input
        type="number"
        name="price"
        class="jazz-cms-input"
        min="0"
        max="10000"
        step="0.01"
        value="<?= htmlspecialchars($performer->price == null ? '' : $performer->price / 100) ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Note</label>
    <textarea name="note_text" class="jazz-cms-textarea" rows="3"><?= htmlspecialchars($performer->note_text ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Audio URL</label>
    <input
        type="text"
        name="audio_url"
        class="jazz-cms-input"
        value="<?= htmlspecialchars($performer->audio_url ?? '') ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Sort Order</label>
    <input
        type="number"
        name="sort_order"
        class="jazz-cms-input"
        value="<?= (int)($performer->sort_order ?? 0) ?>"
    >
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Active</label>
    <select name="is_active" class="jazz-cms-input">
        <option value="1" <?= ((int)($performer->is_active ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
        <option value="0" <?= ((int)($performer->is_active ?? 0) === 0) ? 'selected' : '' ?>>No</option>
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
        Used for performer cards and performer content sections.
    </small>

    <div class="jazz-cms-image-preview-wrap" id="performerPreviewWrap" style="<?= empty($performer->image_path) ? 'display:none;' : '' ?>">
        <p class="jazz-cms-preview-label">Current Performer Image</p>
        <img
            id="performerPreviewImg"
            src="<?= htmlspecialchars($performer->image_path ?? '') ?>"
            class="jazz-cms-image-preview"
            alt="Performer image"
        >
    </div>
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
        Used for the large top banner on the performer detail page.
    </small>

    <div class="jazz-cms-image-preview-wrap" id="performerHeroPreviewWrap" style="<?= empty($performer->hero_image_path) ? 'display:none;' : '' ?>">
        <p class="jazz-cms-preview-label">Current Hero Image</p>
        <img
            id="performerHeroPreviewImg"
            src="<?= htmlspecialchars($performer->hero_image_path ?? '') ?>"
            class="jazz-cms-image-preview"
            alt="Hero image"
        >
    </div>
</div>

<hr class="my-4">

<h3 class="jazz-cms-section-title">Career Highlights</h3>

<?php for ($i = 0; $i < 3; $i++): ?>
    <?php $highlight = $highlights[$i] ?? null; ?>

    <div class="jazz-cms-welcome-card" style="margin-bottom:20px;">
        <input type="hidden" name="highlights[<?= (int)$i ?>][id]" value="<?= (int)($highlight->id ?? 0) ?>">

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Highlight Title</label>
            <input
                type="text"
                name="highlights[<?= (int)$i ?>][title]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($highlight->title ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Description</label>
            <textarea
                name="highlights[<?= (int)$i ?>][description]"
                class="jazz-cms-textarea"
                rows="3"
            ><?= htmlspecialchars($highlight->description ?? '') ?></textarea>
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Sort Order</label>
            <input
                type="number"
                name="highlights[<?= (int)$i ?>][sort_order]"
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
        <input type="hidden" name="tracks[<?= (int)$i ?>][id]" value="<?= (int)($track->id ?? 0) ?>">

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Title</label>
            <input
                type="text"
                name="tracks[<?= (int)$i ?>][title]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->title ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Release Date</label>
            <input
                type="text"
                name="tracks[<?= (int)$i ?>][release_date_text]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->releaseDateText ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Description</label>
            <textarea
                name="tracks[<?= (int)$i ?>][description]"
                class="jazz-cms-textarea"
                rows="3"
            ><?= htmlspecialchars($track->description ?? '') ?></textarea>
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Listen URL</label>
            <input
                type="text"
                name="tracks[<?= (int)$i ?>][listen_url]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->listenUrl ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Image Path</label>
            <input
                type="text"
                name="tracks[<?= (int)$i ?>][image_path]"
                class="jazz-cms-input"
                value="<?= htmlspecialchars($track->imagePath ?? '') ?>"
            >
        </div>

        <div class="jazz-cms-form-row">
            <label class="jazz-cms-label">Sort Order</label>
            <input
                type="number"
                name="tracks[<?= (int)$i ?>][sort_order]"
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
const performerPreviewWrap = document.getElementById('performerPreviewWrap');
const performerPreviewImg = document.getElementById('performerPreviewImg');

performerUploadBox.addEventListener('click', function () {
    performerImageInput.click();
});

performerUploadBox.addEventListener('dragover', function (e) {
    e.preventDefault();
});

performerUploadBox.addEventListener('drop', function (e) {
    e.preventDefault();
    performerImageInput.files = e.dataTransfer.files;
    performerPreviewImg.src = URL.createObjectURL(performerImageInput.files[0]);
    performerPreviewWrap.style.display = '';
});

performerImageInput.addEventListener('change', function () {
    performerPreviewImg.src = URL.createObjectURL(performerImageInput.files[0]);
    performerPreviewWrap.style.display = '';
});

const performerHeroUploadBox = document.getElementById('performerHeroUploadBox');
const performerHeroImageInput = document.getElementById('performerHeroImageInput');
const performerHeroPreviewWrap = document.getElementById('performerHeroPreviewWrap');
const performerHeroPreviewImg = document.getElementById('performerHeroPreviewImg');

performerHeroUploadBox.addEventListener('click', function () {
    performerHeroImageInput.click();
});

performerHeroUploadBox.addEventListener('dragover', function (e) {
    e.preventDefault();
});

performerHeroUploadBox.addEventListener('drop', function (e) {
    e.preventDefault();
    performerHeroImageInput.files = e.dataTransfer.files;
    performerHeroPreviewImg.src = URL.createObjectURL(performerHeroImageInput.files[0]);
    performerHeroPreviewWrap.style.display = '';
});

performerHeroImageInput.addEventListener('change', function () {
    performerHeroPreviewImg.src = URL.createObjectURL(performerHeroImageInput.files[0]);
    performerHeroPreviewWrap.style.display = '';
});
</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>