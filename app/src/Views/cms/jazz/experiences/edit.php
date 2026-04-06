<?php

$pageTitle = 'Edit Experience';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;
$experience = $vm->experience ?? null;

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

<form action="/cms/jazz/experiences/update?id=<?= (int)($experience?->id ?? 0) ?>" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

<input type="hidden" name="id" value="<?= (int)($experience?->id ?? 0) ?>">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Title</label>
<input
type="text"
name="title"
class="jazz-cms-input"
value="<?= htmlspecialchars($experience?->title ?? '') ?>"
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
><?= htmlspecialchars($experience?->description ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Sort Order</label>
<input
type="number"
name="sort_order"
class="jazz-cms-input"
value="<?= (int)($experience?->sortOrder ?? 0) ?>"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Active</label>

<select name="is_active" class="jazz-cms-input">
<option value="1" <?= ((int)($experience?->isActive ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
<option value="0" <?= ((int)($experience?->isActive ?? 0) === 0) ? 'selected' : '' ?>>No</option>
</select>
</div>

<div class="jazz-cms-form-row">

<label class="jazz-cms-label">Experience Image</label>

<div class="jazz-cms-upload-box">

<input
type="file"
name="experience_image"
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

<?php if (!empty($experience?->imagePath)): ?>

<div class="jazz-cms-image-preview-wrap">

<p class="jazz-cms-preview-label">Current Image</p>

<img
src="<?= htmlspecialchars($experience->imagePath) ?>"
class="jazz-cms-image-preview"
>

<p class="jazz-cms-image-path">
<?= htmlspecialchars($experience->imagePath) ?>
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

<?php require __DIR__ . '/../../../partials/footer.php'; ?>