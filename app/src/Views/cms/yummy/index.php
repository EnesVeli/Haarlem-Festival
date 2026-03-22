<?php require __DIR__ . '/../../partials/header.php'; ?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<div class="container py-4 jazz-cms-page">
    <?php include __DIR__ . '/partials/topper.php'; ?>

    <div class="cms-main">
        <?php require __DIR__ . '/partials/tabs.php'; ?>

        <div class="cms-main-section">
            <form class="cms-main-subsection-container" method="post" action="/cms/yummy/home">
                <div class="cms-form-subsection">
                    <label class="cms-form-label">Title:</label>
                    <input type="text" class="cms-text-input" value="<? echo htmlspecialchars($view_model->home_title) ?>">
                </div>

                <div class="cms-form-subsection">
                    <label class="cms-form-label">Subitle:</label>
                    <textarea class="cms-text-text-area"><? echo htmlspecialchars($view_model->home_subtitle) ?></textarea>
                </div>

                <button type="submit" class="cms-submit-button">Save Changes</button>
            </form>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>