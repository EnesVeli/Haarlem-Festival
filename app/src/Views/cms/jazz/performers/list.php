<?php

/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzPerformersCmsViewModel $vm */

$pageTitle = 'Performers';
$pageCSS = 'jazz.css';
$performers = $vm->performers ?? [];

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Performers';
$subtitle = 'Manage jazz performers.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'performers';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">

<div class="jazz-cms-toolbar">

<h2 class="jazz-cms-section-title">All Performers</h2>

<a href="/cms/jazz/performers/create" class="jazz-cms-btn jazz-cms-btn-primary">
Add Performer
</a>

</div>

<div class="jazz-cms-table-wrap">

<table class="jazz-cms-table">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Style</th>
<th>Image</th>
<th>Order</th>
<th>Active</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php if (empty($performers)): ?>

<tr>
<td colspan="7">No performers found.</td>
</tr>

<?php else: ?>

<?php foreach ($performers as $performer): ?>

<tr>

<td><?= (int)($performer->id ?? 0) ?></td>

<td><?= htmlspecialchars($performer->name ?? '') ?></td>

<td><?= htmlspecialchars($performer->performance_style ?? '') ?></td>

<td>

<?php if (!empty($performer->image_path)): ?>

<img
src="<?= htmlspecialchars($performer->image_path) ?>"
class="jazz-cms-image-preview"
style="max-width:80px;"
>

<?php else: ?>

<span class="jazz-cms-help-text">No image</span>

<?php endif; ?>

</td>

<td><?= (int)($performer->sort_order ?? 0) ?></td>

<td>
<?= ((int)($performer->is_active ?? 0) === 1) ? 'Yes' : 'No' ?>
</td>

<td class="jazz-cms-actions">

<a
href="/cms/jazz/performers/edit?id=<?= (int)$performer->id ?>"
class="jazz-cms-btn jazz-cms-btn-outline"
>
Edit
</a>

<a
href="/cms/jazz/performers/delete?id=<?= (int)$performer->id ?>"
class="jazz-cms-btn jazz-cms-btn-danger"
onclick="return confirm('Delete this performer?')"
>
Delete
</a>

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