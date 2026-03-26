<?php require __DIR__ . '/../../partials/header.php'; ?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<div class="container py-4 jazz-cms-page">
    <?php include __DIR__ . '/partials/topper.php'; ?>

    <div class="cms-main">
        <?php require __DIR__ . '/partials/tabs.php'; ?>

        <div class="cms-main-section">
            <?php if(!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
            
            <form class="cms-main-subsection-container" enctype="multipart/form-data" method="post" action="/cms/yummy/home">
                <div class="cms-form-subsection">
                    <label class="cms-form-label">Title:</label>
                    <input type="text" name="title" class="cms-text-input" value="<? echo htmlspecialchars($view_model->home_title) ?>">
                </div>

                <div class="cms-form-subsection">
                    <label class="cms-form-label">Subitle:</label>
                    <textarea name="subtitle" class="cms-text-text-area"><? echo htmlspecialchars($view_model->home_subtitle) ?></textarea>
                </div>

                <div class="cms-form-subsection">
                    <label class="cms-form-label">Topper Image:</label>
                    <img class="cms-show-image" src="<? echo '/assets/uploads/yummy/topper/' . $view_model->topper_path; ?>">
                    <label class="cms-form-sublabel">Change Image:</label>
                    <div class="cms-upload-box" id="upload_box">
                        <input type="file" name="topper_image" id="image" class="cms-file-input" accept="image/*">

                        <div class="cms-upload-inner">
                            <strong>Drop image here</strong>
                            <span>or choose a file</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="cms-submit-button">Save Changes</button>
            </form>
        </div>

    </div>
</div>

<script type="text/javascript">
    const upload_box = document.getElementById('upload_box');
    const image_input = document.getElementById('image');

    if (upload_box && image_input) {
        upload_box.addEventListener('click', function () {
            image_input.click();
        });

        upload_box.addEventListener('dragover', function (e) {
            e.preventDefault();
            upload_box.classList.add('is-dragover');
        });

        upload_box.addEventListener('dragleave', function () {
            upload_box.classList.remove('is-dragover');
        });

        upload_box.addEventListener('drop', function (e) {
            e.preventDefault();
            upload_box.classList.remove('is-dragover');

            if (e.dataTransfer.files.length > 0) {
                image_input.files = e.dataTransfer.files;
            }
        });
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>