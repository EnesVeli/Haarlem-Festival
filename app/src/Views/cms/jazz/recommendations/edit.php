<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzRecommendationsCmsViewModel $vm */

$pageTitle = 'Edit Recommendation';
$pageCSS = 'jazz.css';
$rec = $vm->recommendation ?? null;

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Edit Recommendation';
$subtitle = 'Update recommendation card.';
$buttonText = 'Back to List';
$buttonLink = '/cms/jazz/recommendations';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'recommendations';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">

<div class="jazz-cms-welcome-card">

<h2 class="jazz-cms-section-title">Edit Recommendation</h2>

<form action="/cms/jazz/recommendations/update?id=<?= (int)($rec->id ?? 0) ?>" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

<input type="hidden" name="id" value="<?= (int)($rec->id ?? 0) ?>">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Title</label>
<input
type="text"
name="title"
class="jazz-cms-input"
value="<?= htmlspecialchars($rec->title ?? '') ?>"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Description</label>
<textarea
name="description"
class="jazz-cms-textarea"
rows="4"
><?= htmlspecialchars($rec->description ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">URL</label>
<input
type="text"
name="url"
class="jazz-cms-input"
value="<?= htmlspecialchars($rec->url ?? '') ?>"
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Sort Order</label>
<input
type="number"
name="sort_order"
class="jazz-cms-input"
value="<?= (int)($rec->sortOrder ?? 0) ?>"
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Active</label>

<select name="is_active" class="jazz-cms-input">
<option value="1" <?= ((int)($rec->isActive ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
<option value="0" <?= ((int)($rec->isActive ?? 0) === 0) ? 'selected' : '' ?>>No</option>
</select>

</div>

<div class="jazz-cms-form-row">

<label class="jazz-cms-label">Recommendation Image</label>

<div class="jazz-cms-upload-box" id="recommendationUploadBox">

<input
type="file"
name="recommendation_image"
id="recommendationImageInput"
class="jazz-cms-file-input"
accept="image/*"
>

<div class="jazz-cms-upload-inner">
<strong>Drop image here</strong>
<span>or choose a file</span>
</div>

</div>

<small class="jazz-cms-help-text">
Upload path: <code>/public/uploads/recommendations/</code>
</small>

<?php if (!empty($rec->imagePath)): ?>

<div class="jazz-cms-image-preview-wrap">

<p class="jazz-cms-preview-label">Current Image</p>

<img
src="<?= htmlspecialchars($rec->imagePath) ?>"
class="jazz-cms-image-preview"
>

</div>

<?php endif; ?>

</div>

<div class="jazz-cms-form-actions">

<a href="/cms/jazz/recommendations" class="jazz-cms-btn jazz-cms-btn-outline">
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

const recommendationUploadBox = document.getElementById('recommendationUploadBox');
const recommendationImageInput = document.getElementById('recommendationImageInput');

if (recommendationUploadBox && recommendationImageInput) {

recommendationUploadBox.addEventListener('click', () => recommendationImageInput.click());

recommendationUploadBox.addEventListener('dragover', e => {
e.preventDefault();
recommendationUploadBox.classList.add('is-dragover');
});

recommendationUploadBox.addEventListener('dragleave', () => {
recommendationUploadBox.classList.remove('is-dragover');
});

recommendationUploadBox.addEventListener('drop', e => {

e.preventDefault();
recommendationUploadBox.classList.remove('is-dragover');

if (e.dataTransfer.files.length > 0) {
recommendationImageInput.files = e.dataTransfer.files;
}

});

}

</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>