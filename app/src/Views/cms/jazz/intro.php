<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzIntroCmsViewModel $vm */

$pageTitle = 'Intro Content';
$pageCSS = 'jazz.css';
$intro = $vm->intro;

require __DIR__ . '/../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Intro Content';
$subtitle = 'Edit the introduction text shown on the Jazz homepage.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

    <?php
    $activeTab = 'intro';
    require __DIR__ . '/partials/tabs.php';
    ?>

    <div class="jazz-cms-section">

        <div class="jazz-cms-welcome-card">
            <h2 class="jazz-cms-section-title">Edit Intro</h2>

            <form action="/cms/jazz/intro/update" method="POST" class="jazz-cms-form">

                <input type="hidden" name="id" value="<?= htmlspecialchars($intro?->id ?? '') ?>">

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Title</label>
                    <input
                        type="text"
                        name="title"
                        class="jazz-cms-input"
                        value="<?= htmlspecialchars($intro?->title ?? '') ?>"
                        required
                    >
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Description</label>
                    <textarea
                        name="description"
                        class="jazz-cms-textarea"
                        rows="6"
                        required
                    ><?= htmlspecialchars($intro?->description ?? '') ?></textarea>
                </div>

                <div class="jazz-cms-form-actions">
                    <button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">
                        Save Intro
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>