<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzRecommendationsCmsViewModel $vm */

$pageTitle = 'Recommendations';
$pageCSS = 'jazz.css';
$recommendations = $vm->recommendations ?? [];

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Recommendations';
$subtitle = 'Manage recommendation cards.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

<?php
$activeTab = 'recommendations';
require __DIR__ . '/../partials/tabs.php';
?>

<div class="jazz-cms-section">

<div class="jazz-cms-toolbar">

<h2 class="jazz-cms-section-title">All Recommendations</h2>

<a href="/cms/jazz/recommendations/create" class="jazz-cms-btn jazz-cms-btn-primary">
Add Recommendation
</a>

</div>

<div class="jazz-cms-table-wrap">

<table class="jazz-cms-table">

<thead>
<tr>
<th>ID</th>
<th>Title</th>
<th>Image</th>
<th>Order</th>
<th>Active</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php if (empty($recommendations)): ?>

<tr>
<td colspan="6">No recommendations found.</td>
</tr>

<?php else: ?>

<?php foreach ($recommendations as $rec): ?>

<tr>

<td><?= (int)($rec->id ?? 0) ?></td>

<td><?= htmlspecialchars($rec->title ?? '') ?></td>

<td>

<?php if (!empty($rec->imagePath)): ?>

<img
src="<?= htmlspecialchars($rec->imagePath) ?>"
class="jazz-cms-image-preview"
style="max-width:80px;"
>

<?php else: ?>

<span class="jazz-cms-help-text">No image</span>

<?php endif; ?>

</td>

<td><?= (int)($rec->sortOrder ?? 0) ?></td>

<td>
<?= ((int)($rec->isActive ?? 0) === 1) ? 'Yes' : 'No' ?>
</td>

<td class="jazz-cms-actions">

<a
href="/cms/jazz/recommendations/edit?id=<?= (int)$rec->id ?>"
class="jazz-cms-btn jazz-cms-btn-outline"
>
Edit
</a>

<form method="POST" action="/cms/jazz/recommendations/delete" style="display:inline" onsubmit="return confirm('Delete this recommendation?')">
    <input type="hidden" name="id" value="<?= (int)$rec->id ?>">
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