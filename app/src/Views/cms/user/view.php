<?php
/** @var \App\ViewModels\User\ViewUserViewModel $view_model */
/** @var ?string $error_message */
/** @var ?string $success_message */

$pageTitle = 'Users Cms - View';
$pageCSS = 'user.css'; 
?>

<?php require __DIR__ . '/../../partials/header.php'; ?>

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

            <?php if(!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <? if(isset($view_model)): ?>  
                <form id="main_form" action="/cms/user/edit" enctype="multipart/form-data" method="post">
                    <div class="cms-main-subsection-container">
                        <input id="user_id" name="user_id" type="hidden" value="<? echo $view_model->user->user_id; ?>">

                        <div class="cms-form-subsection">
                            <label class="cms-form-label" for="name">Name:</label>
                            <input id="name" name="name" type="text" class="cms-text-input" maxlength="128" value="<? echo htmlspecialchars($view_model->user->name) ?>">
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label" for="email">Email:</label>
                            <input id="email" name="email" type="text" class="cms-text-input" maxlength="128" value="<? echo htmlspecialchars($view_model->user->email) ?>">
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label" for="password">Change Password:</label>
                            <input id="password" name="password" type="password" class="cms-text-input">
                        </div>            

                        <div class="cms-form-subsection">
                            <label class="cms-form-label" for="password">Regestration Date:</label>
                            <div class="cms-text-input"><?= $view_model->user->registered_at->format('d.m.Y H:i:s') ?></div>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label" for="active">Active:</label>
                            <input id="active" name="active" type="hidden" value="<? echo $view_model->user->active; ?>">
                            <select id="active_sel" class="cms-dropdown-input" onchange="activeSelectValueChanged(this)">
                                <option value="1" <? echo ($view_model->user->active ? 'selected' : ''); ?>>Yes</option>
                                <option value="0" style="color: red;" <? echo ($view_model->user->active ? '' : 'selected'); ?>>No</option>
                            </select>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label" for="role">Role:</label>
                            <input id="role" name="role" type="hidden" value="<? echo $view_model->user->role->value; ?>">
                            <select id="role_sel" class="cms-dropdown-input" onchange="roleSelectValueChanged(this)">
                                <? foreach($view_model->roles as $role): ?>
                                    <option value="<?= $role ?>" <? echo ($view_model->user->role->value == $role ? 'selected' : ''); ?>><?= ucfirst($role) ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>

                        <div class="cms-form-subsection-row">
                            <div class="cms-image-subsection-container">
                                <label class="cms-form-label">Profile Picture:</label>
                                <img class="cms-show-image" src="<?= $view_model->user->profile_picture_url; ?>">
                            </div>
                            <div class="cms-image-subsection-container">
                                <div class="cms-upload-box" id="upload_box">
                                    <input type="file" name="main_img_path" id="main_img_path" class="cms-file-input" accept="image/*" onchange="previewMain()">

                                    <div class="cms-upload-inner">
                                        <div id="img_none">
                                            <strong>Change Profile</strong>
                                            <div>
                                                <strong>Drop image here</strong>
                                                <span>or choose a file</span>
                                            </div>                  
                                        </div>
                                        <div id="img_is" class="cms-upload-container" style="display: none;">
                                            <strong>Change Profile</strong>
                                            <div class="cms-upload-preview-container">
                                                <img id="topper_image_preview" class="cms-upload-preview" src="#">
                                            </div>             
                                        </div>              
                                    </div>
                                </div>
                            </div>  
                        </div>                       
                    
                        <button class="cms-submit-button" type="submit">Save Changes</button>
                    </div>
                </form>      
            <? endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    let show_preview = false; // Is preview image of main image displayed.

    initImageInput('upload_box', 'main_img_path', previewMain) // Init change image input for main image

    // Init image input for image change
    function initImageInput(upload_container, input, preview_func){
        let upload_box = document.getElementById(upload_container);
        let image_input = document.getElementById(input);

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
                preview_func();
            }
        });
    }

    // Update preview image of main image
    function previewMain() {
        preview('topper_image_preview', 'main_img_path', 'img_is', 'img_none');
    }

    // Update preview image of one of the inputs
    function preview(preview, input, preview_image, preview_text){
        var preview = document.getElementById(preview);
        var file = document.getElementById(input).files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);

            if(!show_preview){
                document.getElementById(preview_text).style = "display: none;";
                document.getElementById(preview_image).style = "display: flex;";

                show_preview = true;
            }
        } else {
            if(show_preview){
                document.getElementById(preview_text).style = "display: block;";
                document.getElementById(preview_image).style = "display: none;";

                show_preview = false;
            }

            preview.src = "";
        }
    }

    function activeSelectValueChanged(caller){
        document.getElementById("active").value = caller.value;
    }

    function roleSelectValueChanged(caller){
        document.getElementById("role").value = caller.value;
    }

    function mainSubmitButtonClick(){
        document.getElementById('main_form').submit();
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>