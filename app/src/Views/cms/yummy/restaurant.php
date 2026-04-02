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

            <? if(isset($view_model)): ?>        
                <form class="cms-main-subsection-container" enctype="multipart/form-data" method="post" action="/cms/yummy/restaurant">
                    <input type="hidden" name="restaurant_id" value="<? echo $view_model->res->restaurant_id; ?>">

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Name:</label>
                        <input type="text" name="name" class="cms-text-input" value="<? echo htmlspecialchars($view_model->res->name) ?>">
                    </div>

                     <div class="cms-form-subsection">
                        <label class="cms-form-label">Active:</label>
                        <label class="cms-input-checkbox-container">
                            <input type="checkbox" <? echo ($view_model->res->active ? 'checked' : '');?>>
                            <span class="cms-input-checkbox-checkmark"></span>
                        </label>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Rating:</label>
                        <input name="rating" type="number" class="cms-number-input" value="<? echo htmlspecialchars($view_model->res->rating) ?>" step="0.1" min="0" max="5">
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Cost Rating:</label>
                        <input type="text" list="cost_rating" />
                        <datalist id="cost_rating">
                            <option value="1">€</option>
                            <option value="2">€€</option>
                            <option value="3">€€€</option>
                        </datalist>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Mini Text:</label>
                        <textarea name="mini_text" class="cms-text-text-area text-mid"><? echo htmlspecialchars($view_model->res->mini_text) ?></textarea>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Text:</label>
                        <textarea name="text" class="cms-text-text-area text-long"><? echo htmlspecialchars($view_model->res->text) ?></textarea>
                    </div>

                    <div class="cms-form-subsection-row">
                        <div class="cms-image-subsection-container">
                            <label class="cms-form-label">Main Image:</label>
                            <img class="cms-show-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->res->main_img_path; ?>">
                        </div>
                        <div class="cms-image-subsection-container">
                            <div class="cms-upload-box" id="upload_box">
                                <input type="file" name="main_img_path" id="image" class="cms-file-input" accept="image/*">

                                <div class="cms-upload-inner">
                                    <strong>Change Image</strong>
                                    <strong>Drop image here</strong>
                                    <span>or choose a file</span>
                                </div>
                            </div>
                        </div>  
                    </div>

                    <button type="submit" class="cms-submit-button">Save Changes</button>
                </form>
            <? endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>