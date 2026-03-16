<?php

$pageTitle = 'Edit Experience';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;
$experience = $vm->experience ?? [];

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Edit Experience';
$subtitle = 'Update an experience card.';
$buttonText = 'Back to List';
$buttonLink = '/cms/jazz/experiences';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'experiences';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">

<div class="jazz-cms-welcome-card">

<h2 class="jazz-cms-section-title">Edit Experience</h2>

<form action="/cms/jazz/experiences/update?id=<?= (int)($experience['id'] ?? 0) ?>" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

<input type="hidden" name="id" value="<?= (int)($experience['id'] ?? 0) ?>">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Title</label>
<input
type="text"
name="title"
class="jazz-cms-input"
value="<?= htmlspecialchars($experience['title'] ?? '') ?>"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Description</label>
<textarea
name="description"
class="jazz-cms-textarea"
rows="5"
required
><?= htmlspecialchars($experience['description'] ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Sort Order</label>
<input
type="number"
name="sort_order"
class="jazz-cms-input"
value="<?= (int)($experience['sort_order'] ?? 0) ?>"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Active</label>

<select name="is_active" class="jazz-cms-input">
<option value="1" <?= ((int)($experience['is_active'] ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
<option value="0" <?= ((int)($experience['is_active'] ?? 0) === 0) ? 'selected' : '' ?>>No</option>
</select>
</div>

<div class="jazz-cms-form-row">

<label class="jazz-cms-label">Experience Image</label>

<div class="jazz-cms-upload-box" id="experienceUploadBox">

<input
type="file"
name="experience_image"
id="experienceImageInput"
class="jazz-cms-file-input"
accept="image/*"
>

<div class="jazz-cms-upload-inner">
<strong>Drop image here</strong>
<span>or choose a file</span>
</div>

</div>

<small class="jazz-cms-help-text">
Upload path: <code>/public/uploads/experiences/</code>
</small>

<?php if (!empty($experience['image_path'])): ?>

<div class="jazz-cms-image-preview-wrap">

<p class="jazz-cms-preview-label">Current Image</p>

<img
src="<?= htmlspecialchars($experience['image_path']) ?>"
class="jazz-cms-image-preview"
>

<p class="jazz-cms-image-path">
<?= htmlspecialchars($experience['image_path']) ?>
</p>

</div>

<?php endif; ?>

</div>

<div class="jazz-cms-form-actions">

<a href="/cms/jazz/experiences" class="jazz-cms-btn jazz-cms-btn-outline">
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

const experienceUploadBox = document.getElementById('experienceUploadBox');
const experienceImageInput = document.getElementById('experienceImageInput');

if (experienceUploadBox && experienceImageInput) {

experienceUploadBox.addEventListener('click', function () {
experienceImageInput.click();
});

experienceUploadBox.addEventListener('dragover', function (e) {
e.preventDefault();
experienceUploadBox.classList.add('is-dragover');
});

experienceUploadBox.addEventListener('dragleave', function () {
experienceUploadBox.classList.remove('is-dragover');
});

experienceUploadBox.addEventListener('drop', function (e) {

e.preventDefault();
experienceUploadBox.classList.remove('is-dragover');

if (e.dataTransfer.files.length > 0) {
experienceImageInput.files = e.dataTransfer.files;
}

});

}

</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>