<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzRecommendationsCmsViewModel $vm */

$pageTitle = 'Create Recommendation';
$pageCSS = 'jazz.css';

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Create Recommendation';
$subtitle = 'Add a new recommendation card.';
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

<h2 class="jazz-cms-section-title">New Recommendation</h2>

<form action="/cms/jazz/recommendations/store" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Title</label>
<input type="text" name="title" class="jazz-cms-input" required>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Description</label>
<textarea name="description" class="jazz-cms-textarea" rows="4"></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">URL</label>
<input type="text" name="url" class="jazz-cms-input">
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

<div class="jazz-cms-image-preview-wrap" id="recommendationPreviewWrap" style="display:none;">
<p class="jazz-cms-preview-label">Preview</p>
<img id="recommendationPreviewImg" src="" class="jazz-cms-image-preview">
</div>

</div>

<div class="jazz-cms-form-actions">

<a href="/cms/jazz/recommendations" class="jazz-cms-btn jazz-cms-btn-outline">
Cancel
</a>

<button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">
Create Recommendation
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
const recommendationPreviewWrap = document.getElementById('recommendationPreviewWrap');
const recommendationPreviewImg = document.getElementById('recommendationPreviewImg');

recommendationUploadBox.addEventListener('click', function () {
    recommendationImageInput.click();
});

recommendationUploadBox.addEventListener('dragover', function (e) {
    e.preventDefault();
});

recommendationUploadBox.addEventListener('drop', function (e) {
    e.preventDefault();
    recommendationImageInput.files = e.dataTransfer.files;
    recommendationPreviewImg.src = URL.createObjectURL(recommendationImageInput.files[0]);
    recommendationPreviewWrap.style.display = '';
});

recommendationImageInput.addEventListener('change', function () {
    recommendationPreviewImg.src = URL.createObjectURL(recommendationImageInput.files[0]);
    recommendationPreviewWrap.style.display = '';
});
</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>