<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzLocationsCmsViewModel $vm */

$pageTitle = 'Edit Location';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;
$location = $vm->location ?? null;

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Edit Location';
$subtitle = 'Update location information.';
$buttonText = 'Back to List';
$buttonLink = '/cms/jazz/locations';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'locations';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">

<div class="jazz-cms-welcome-card">

<h2 class="jazz-cms-section-title">Edit Location</h2>

<form action="/cms/jazz/locations/update?id=<?= (int)($location->id ?? 0) ?>" method="POST" class="jazz-cms-form">

<input type="hidden" name="id" value="<?= (int)($location->id ?? 0) ?>">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Name</label>
<input
type="text"
name="name"
class="jazz-cms-input"
value="<?= htmlspecialchars($location->name ?? '') ?>"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Address</label>
<input
type="text"
name="address"
class="jazz-cms-input"
value="<?= htmlspecialchars($location->address ?? '') ?>"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Google Maps Embed URL</label>
<textarea
name="google_maps_embed_url"
class="jazz-cms-textarea"
rows="4"
><?= htmlspecialchars($location->google_maps_embed_url ?? '') ?></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Active</label>

<select name="is_active" class="jazz-cms-input">
<option value="1" <?= ((int)($location->is_active ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
<option value="0" <?= ((int)($location->is_active ?? 0) === 0) ? 'selected' : '' ?>>No</option>
</select>

</div>

<div class="jazz-cms-form-actions">

<a href="/cms/jazz/locations" class="jazz-cms-btn jazz-cms-btn-outline">
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