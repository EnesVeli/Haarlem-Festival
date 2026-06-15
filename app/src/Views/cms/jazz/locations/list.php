<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzLocationsCmsViewModel $vm */

$pageTitle = 'Locations';
$pageCSS = 'jazz.css';
$locations = $vm->locations ?? [];

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Locations';
$subtitle = 'Manage jazz event locations.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'locations';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">

<div class="jazz-cms-toolbar">

<h2 class="jazz-cms-section-title">All Locations</h2>

<a href="/cms/jazz/locations/create" class="jazz-cms-btn jazz-cms-btn-primary">
Add Location
</a>

</div>

<div class="jazz-cms-table-wrap">

<table class="jazz-cms-table">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Address</th>
<th>Active</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php if (empty($locations)): ?>

<tr>
<td colspan="5">No locations found.</td>
</tr>

<?php else: ?>

<?php foreach ($locations as $loc): ?>

<tr>

<td><?= (int)($loc->id ?? 0) ?></td>

<td><?= htmlspecialchars($loc->name ?? '') ?></td>

<td><?= htmlspecialchars($loc->address ?? '') ?></td>

<td>
<?= ((int)($loc->is_active ?? 0) === 1) ? 'Yes' : 'No' ?>
</td>

<td class="jazz-cms-actions">

<a
href="/cms/jazz/locations/edit?id=<?= (int)$loc->id ?>"
class="jazz-cms-btn jazz-cms-btn-outline"
>
Edit
</a>

<form method="POST" action="/cms/jazz/locations/delete" style="display:inline" onsubmit="return confirm('Delete this location?')">
    <input type="hidden" name="id" value="<?= (int)$loc->id ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Framework\Session::csrfToken()) ?>">
    <button type="submit" class="jazz-cms-btn jazz-cms-btn-danger">Delete</button>
</form>

</td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>