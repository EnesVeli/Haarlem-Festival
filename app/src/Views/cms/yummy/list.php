<?php
/** @var  \App\ViewModels\Yummy\Cms\YummyListViewModel $view_model */
/** @var ?string $error_message */

$pageTitle = 'Yummy CMS - List';
$pageCSS = 'yummy.css'; 
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
            
            <form class="cms-main-subsection-container" enctype="multipart/form-data" method="post" action="/cms/yummy/list">
                <div class="cms-form-subsection">
                    <label class="cms-form-label">Title:</label>
                    <input type="text" name="title" class="cms-text-input" value="<? echo htmlspecialchars($view_model->list_title) ?>">
                </div>

                <div class="cms-form-subsection">
                    <label class="cms-form-label">Subitle:</label>
                    <textarea name="subtitle" class="cms-text-text-area text-short"><? echo htmlspecialchars($view_model->list_subtitle) ?></textarea>
                </div>

                <div class="cms-form-subsection-row">
                    <div class="cms-image-subsection-container">
                        <label class="cms-form-label">Topper Image:</label>
                        <img class="cms-show-image" src="<? echo '/assets/uploads/yummy/topper/' . $view_model->list_image; ?>">
                    </div>
                    <div class="cms-image-subsection-container">
                        <div class="cms-upload-box" id="upload_box">
                            <input type="file" name="topper_image" id="image" class="cms-file-input" accept="image/*" onchange="previewFile()">

                            <div class="cms-upload-inner">
                                <div id="img_none">
                                    <strong>Change Image</strong>
                                    <div>
                                        <strong>Drop image here</strong>
                                        <span>or choose a file</span>
                                    </div>                  
                                </div>
                                <div id="img_is" class="cms-upload-container" style="display: none;">
                                    <strong>Change Image</strong>
                                    <div class="cms-upload-preview-container">
                                        <img id="topper_image_preview" class="cms-upload-preview" src="#">
                                    </div>             
                                </div>              
                            </div>
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

    let show_preview = false;

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
                previewFile();
            }
        });
    }

    function previewFile() {
        var preview = document.getElementById('topper_image_preview');
        var file = document.getElementById('image').files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);

            if(!show_preview){
                document.getElementById('img_none').style = "display: none;";
                document.getElementById('img_is').style = "display: flex;";

                show_preview = true;
            }
        } else {
            if(show_preview){
                document.getElementById('img_none').style = "display: block;";
                document.getElementById('img_is').style = "display: none;";

                show_preview = false;
            }

            preview.src = "";
        }
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>