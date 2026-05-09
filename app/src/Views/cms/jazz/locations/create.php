<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzLocationsCmsViewModel $vm */

$pageTitle = 'Create Location';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Create Location';
$subtitle = 'Add a new event location.';
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

<h2 class="jazz-cms-section-title">New Location</h2>

<form action="/cms/jazz/locations/store" method="POST" class="jazz-cms-form">

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Name</label>
<input
type="text"
name="name"
class="jazz-cms-input"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Address</label>
<input
type="text"
name="address"
class="jazz-cms-input"
required
>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Google Maps Embed URL</label>
<textarea
name="google_maps_embed_url"
class="jazz-cms-textarea"
rows="4"
placeholder="Paste Google Maps embed URL here"
></textarea>
</div>

<div class="jazz-cms-form-row">
<label class="jazz-cms-label">Active</label>

<select name="is_active" class="jazz-cms-input">
<option value="1">Yes</option>
<option value="0">No</option>
</select>

</div>

<div class="jazz-cms-form-actions">

<a href="/cms/jazz/locations" class="jazz-cms-btn jazz-cms-btn-outline">
Cancel
</a>

<button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">
Create Location
</button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>