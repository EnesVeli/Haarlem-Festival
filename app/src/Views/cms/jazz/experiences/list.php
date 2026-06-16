<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzExperiencesCmsViewModel $vm */

$pageTitle = 'Experiences';
$pageCSS = 'jazz.css';
$experiences = $vm->experiences ?? [];

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Experiences';
$subtitle = 'Manage the experience cards shown on the Jazz homepage.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/../partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

    <?php
    $activeTab = 'experiences';
    require __DIR__ . '/../partials/tabs.php';
    ?>

    <div class="jazz-cms-section">

        <div class="jazz-cms-toolbar">
            <h2 class="jazz-cms-section-title">All Experiences</h2>

            <a href="/cms/jazz/experiences/create" class="jazz-cms-btn jazz-cms-btn-primary">
                Add Experience
            </a>
        </div>

        <div class="jazz-cms-table-wrap">
            <table class="jazz-cms-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Order</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($experiences)): ?>
                        <tr>
                            <td colspan="7">No experiences found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($experiences as $experience): ?>
                            <tr>
                                <td><?= (int) ($experience->id ?? 0) ?></td>

                                <td><?= htmlspecialchars($experience->title ?? '') ?></td>

                                <td><?= htmlspecialchars($experience->description ?? '') ?></td>

                                <td>
                                    <?php if (!empty($experience->imagePath)): ?>
                                        <img
                                            src="<?= htmlspecialchars($experience->imagePath) ?>"
                                            alt="Experience image"
                                            class="jazz-cms-image-preview"
                                            style="max-width:100px;"
                                        >
                                    <?php else: ?>
                                        <span class="jazz-cms-help-text">No image</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= (int) ($experience->sortOrder ?? 0) ?></td>

                                <td>
                                    <?= ((int) ($experience->isActive ?? 0) === 1) ? 'Yes' : 'No' ?>
                                </td>

                                <td class="jazz-cms-actions">
                                    <a
                                        href="/cms/jazz/experiences/edit?id=<?= (int) ($experience->id ?? 0) ?>"
                                        class="jazz-cms-btn jazz-cms-btn-outline"
                                    >
                                        Edit
                                    </a>

                                    <form method="POST" action="/cms/jazz/experiences/delete" style="display:inline" onsubmit="return confirm('Delete this experience?')">
                                        <input type="hidden" name="id" value="<?= (int) ($experience->id ?? 0) ?>">
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