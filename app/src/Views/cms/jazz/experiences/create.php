<?php

$pageTitle = 'Create Experience';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;

require __DIR__ . '/../../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Create Experience';
$subtitle = 'Add a new experience card for the Jazz homepage.';
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
            <h2 class="jazz-cms-section-title">New Experience</h2>

            <form action="/cms/jazz/experiences/store" method="POST" enctype="multipart/form-data" class="jazz-cms-form">

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Title</label>
                    <input
                        type="text"
                        name="title"
                        class="jazz-cms-input"
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
                    ></textarea>
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Sort Order</label>
                    <input
                        type="number"
                        name="sort_order"
                        class="jazz-cms-input"
                        value="0"
                        required
                    >
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Active</label>
                    <select name="is_active" class="jazz-cms-input">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="jazz-cms-form-row">
                    <label class="jazz-cms-label">Experience Image</label>

                    <div class="jazz-cms-upload-box" id="experienceUploadBox">
                        <input
                            type="file"
                            name="experience_image"
                            id="experienceImageInput"
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
                </div>

                <div class="jazz-cms-form-actions">
                    <a href="/cms/jazz/experiences" class="jazz-cms-btn jazz-cms-btn-outline">
                        Cancel
                    </a>

                    <button type="submit" class="jazz-cms-btn jazz-cms-btn-primary">
                        Create Experience
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

</div>

<script>
const experienceUploadBox = document.getElementById('experienceUploadBox');
const experienceImageInput = document.getElementById('experienceImageInput');

if (experienceUploadBox && experienceImageInput) {
    experienceUploadBox.addEventListener('click', function () {
        experienceImageInput.click();
    });

    experienceUploadBox.addEventListener('dragover', function (e) {
        e.preventDefault();
        experienceUploadBox.classList.add('is-dragover');
    });

    experienceUploadBox.addEventListener('dragleave', function () {
        experienceUploadBox.classList.remove('is-dragover');
    });

    experienceUploadBox.addEventListener('drop', function (e) {
        e.preventDefault();
        experienceUploadBox.classList.remove('is-dragover');

        if (e.dataTransfer.files.length > 0) {
            experienceImageInput.files = e.dataTransfer.files;
        }
    });
}
</script>

<?php require __DIR__ . '/../../../partials/footer.php'; ?>