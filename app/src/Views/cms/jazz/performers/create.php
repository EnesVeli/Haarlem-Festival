<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzPerformersCmsViewModel $vm */

$pageTitle = 'Create Performer';
$pageCSS = 'jazz.css';

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Create Performer';
$subtitle = 'Add a new jazz performer.';
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

<h2 class="jazz-cms-section-title">New Performer</h2>

<form action="/cms/jazz/performers/store" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Name</label>
<input type="text" name="name" class="jazz-cms-input" required>
</div>

<div class="jazz-cms-form-row">
    <label class="jazz-cms-label">Hero Image</label>
    <input type="file" name="performer_hero_image" class="jazz-cms-input">
    <p class="jazz-cms-help">This image is used in the top banner of the performer detail page.</p>
</div>
<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Performance Style</label>
<input type="text" name="performance_style" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Bio</label>
<textarea name="bio" class="jazz-cms-textarea" rows="5"></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Event Date</label>
<input type="date" name="date" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Event Start Time</label>
<input type="time" name="start_time" class="jazz-cms-input">

<label class="jazz-cms-label">Event End Time</label>
<input type="time" name="end_time" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Venue Name</label>
<input type="text" name="venue_name" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Venue Address</label>
<input type="text" name="venue_address" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Price</label>
<input type="number" name="price" min="0" max="10000" step="0.01" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Note</label>
<textarea name="note_text" class="jazz-cms-textarea" rows="3"></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Audio URL</label>
<input type="text" name="audio_url" class="jazz-cms-input">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Sort Order</label>
<input type="number" name="sort_order" class="jazz-cms-input" value="0">
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Active</label>

<select name="is_active" class="jazz-cms-input">
<option value="1">Yes</option>
<option value="0">No</option>
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

<div class="jazz-cms-image-preview-wrap" id="performerPreviewWrap" style="display:none;">
<p class="jazz-cms-preview-label">Preview</p>
<img id="performerPreviewImg" src="" class="jazz-cms-image-preview">
</div>

</div>

<div class="jazz-cms-form-actions">

<a href="/cms/jazz/performers" class="jazz-cms-btn jazz-cms-btn-outline">
Cancel
</a>

<button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">
Create Performer
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
</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>