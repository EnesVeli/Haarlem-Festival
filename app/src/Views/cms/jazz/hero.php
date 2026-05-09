<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzHeroCmsViewModel $vm */

$pageTitle = 'Hero Section';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;
$hero = $vm->hero;

require __DIR__ . '/../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Hero Section';
$subtitle = 'Manage the Jazz homepage hero content.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

    <?php
    $activeTab = 'hero';
    require __DIR__ . '/partials/tabs.php';
    ?>

    <div class="jazz-cms-section">

        <div class="jazz-cms-welcome-card">
            <h2 class="jazz-cms-section-title">Current Hero</h2>

            <form action="/cms/jazz/hero/update" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

                <input type="hidden" name="id" value="<?= htmlspecialchars($hero?->id ?? '') ?>">

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Title</label>
                    <input
                        type="text"
                        name="title"
                        class="jazz-cms-input"
                        value="<?= htmlspecialchars($hero?->title ?? '') ?>"
                        required
                    >
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Subtitle</label>
                    <textarea
                        name="subtitle"
                        class="jazz-cms-textarea"
                        rows="4"
                    ><?= htmlspecialchars($hero?->subtitle ?? '') ?></textarea>
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Hero Image</label>

                    <div class="jazz-cms-upload-box" id="heroUploadBox">
                        <input
                            type="file"
                            name="hero_image"
                            id="heroImageInput"
                            class="jazz-cms-file-input"
                            accept="image/*"
                        >

                        <div class="jazz-cms-upload-inner">
                            <strong>Drop image here</strong>
                            <span>or choose a file</span>
                        </div>
                    </div>

                    <small class="jazz-cms-help-text">
                        Upload path: <code>/public/uploads/hero/</code>
                    </small>

                    <?php if (!empty($hero?->imagePath)): ?>
                        <div class="jazz-cms-image-preview-wrap">
                            <p class="jazz-cms-preview-label">Current Image</p>
                            <img
                                src="<?= htmlspecialchars($hero->imagePath) ?>"
                                alt="Hero image"
                                class="jazz-cms-image-preview"
                            >
                            <p class="jazz-cms-image-path">
                                <?= htmlspecialchars($hero->imagePath) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Active</label>
                    <select name="is_active" class="jazz-cms-input">
                        <option value="1" <?= ((int)($hero?->isActive ?? 0) === 1) ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= ((int)($hero?->isActive ?? 0) === 0) ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="jazz-cms-form-actions">
                    <button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">Save Hero</button>
                </div>

            </form>
        </div>

    </div>

</div>

</div>

<script>
const heroUploadBox = document.getElementById('heroUploadBox');
const heroImageInput = document.getElementById('heroImageInput');

if (heroUploadBox && heroImageInput) {
    heroUploadBox.addEventListener('click', function () {
        heroImageInput.click();
    });

    heroUploadBox.addEventListener('dragover', function (e) {
        e.preventDefault();
        heroUploadBox.classList.add('is-dragover');
    });

    heroUploadBox.addEventListener('dragleave', function () {
        heroUploadBox.classList.remove('is-dragover');
    });

    heroUploadBox.addEventListener('drop', function (e) {
        e.preventDefault();
        heroUploadBox.classList.remove('is-dragover');

        if (e.dataTransfer.files.length > 0) {
            heroImageInput.files = e.dataTransfer.files;
        }
    });
}
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>