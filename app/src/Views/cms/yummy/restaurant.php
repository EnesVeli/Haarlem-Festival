<?php
/** @var  \App\ViewModels\Yummy\Cms\YummyRestaurantViewModel $view_model */
/** @var ?string $error_message */
/** @var ?string $success_message */

$pageTitle = 'Yummy CMS - New Restauran';
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

            <?php if(!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <? if(isset($view_model)): ?>  
                <form id="main_form" action="/cms/yummy/restaurant" enctype="multipart/form-data" method="post">
                    <div class="cms-main-subsection-container">
                        <input id="restaurant_id" name="restaurant_id" type="hidden" value="<? echo $view_model->res->restaurant_id; ?>">

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Name:</label>
                            <input id="name" name="name" type="text" class="cms-text-input" maxlength="64" value="<? echo htmlspecialchars($view_model->res->name) ?>">
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Active:</label>
                            <input id="active" name="active" type="hidden" value="<? echo $view_model->res->active; ?>">
                            <select id="active_sel" class="cms-dropdown-input" onchange="activeSelectValueChanged(this)">
                                <option value="1" <? echo ($view_model->res->active ? 'selected' : ''); ?>>Yes</option>
                                <option value="0" style="color: red;" <? echo ($view_model->res->active ? '' : 'selected'); ?>>No</option>
                            </select>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Rating:</label>
                            <input id="rating" name="rating" type="number" class="cms-number-input" value="<? echo htmlspecialchars($view_model->res->rating) ?>" step="0.1" min="0" max="5">
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Cost Rating:</label>
                            <input id="cost_rating" name="cost_rating" type="hidden" value="<? echo $view_model->res->cost_rating; ?>">
                            <select id="cost_rating_sel" class="cms-dropdown-input" onchange="costRatingSelectValueChanged(this)">
                                <option value="1" <? echo ($view_model->res->cost_rating == 1 ? 'selected' : ''); ?>>€</option>
                                <option value="2" <? echo ($view_model->res->cost_rating == 2 ? 'selected' : ''); ?>>€€</option>
                                <option value="3" <? echo ($view_model->res->cost_rating == 3 ? 'selected' : ''); ?>>€€€</option>
                            </select>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Mini Text:</label>
                            <textarea id="mini_text" name="mini_text" class="cms-text-text-area text-mid" maxlength="256"><? echo htmlspecialchars($view_model->res->mini_text) ?></textarea>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Text:</label>
                            <textarea id="text" name="text" class="cms-text-text-area text-long" maxlength="2048"><? echo htmlspecialchars($view_model->res->text) ?></textarea>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Address:</label>
                            <input id="address_text" name="address_text" type="text" class="cms-text-input" maxlength="128" value="<? echo htmlspecialchars($view_model->res->address_text) ?>">
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Address Google Uri:</label>
                            <div class="cms-uri-input-container">
                                <input id="address_uri" name="address_uri" type="text" maxlength="256" value="<? echo htmlspecialchars($view_model->res->address_uri) ?>">
                                <button type="button" onclick="googleUriButtonClick()">View</button>
                            </div>            
                            <div>*It should be part of uri in google maps: https://www.google.com/maps/place/<strong>Ratatouille+Food+%26+Wine</strong>/...*</div>
                        </div>

                        <div class="cms-form-subsection">
                            <label class="cms-form-label">Website Link:</label>
                            <div class="cms-uri-input-container">
                                <input id="website_link" name="website_link" type="text" maxlength="256" value="<? echo htmlspecialchars($view_model->res->website_link) ?>">
                                <button type="button" onclick="websiteLinkButtonClick()">View</button>
                            </div>           
                        </div>

                        <div class="cms-form-subsection">
                            <h1 class="cms-form-subsection-big-label">Opening Hours</h1>
                            <div class="cms-opening-hours-container">
                                <div>
                                    <label>Monday:</label>
                                    <input id="opening_hours_monday" name="opening_hours_monday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->monday) ?>">
                                </div>
                                <div>
                                    <label>Tuesday:</label>
                                    <input id="opening_hours_tuesday" name="opening_hours_tuesday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->tuesday) ?>">
                                </div>
                                <div>
                                    <label>Wednesday:</label>
                                    <input id="opening_hours_wednesday" name="opening_hours_wednesday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->wednesday) ?>">
                                </div>
                                <div>
                                    <label>Thursday:</label>
                                    <input id="opening_hours_thursday" name="opening_hours_thursday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->thursday) ?>">
                                </div>
                                <div>
                                    <label>Friday:</label>
                                    <input id="opening_hours_friday" name="opening_hours_friday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->friday) ?>">
                                </div>
                                <div>
                                    <label>Saturday:</label>
                                    <input id="opening_hours_saturday" name="opening_hours_saturday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->saturday) ?>">
                                </div>
                                <div>
                                    <label>Sunday:</label>
                                    <input id="opening_hours_sunday" name="opening_hours_sunday" type="text" maxlength="64" value="<? echo htmlspecialchars($view_model->hours->sunday) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="cms-form-subsection-row">
                            <div class="cms-image-subsection-container">
                                <label class="cms-form-label">Main Image:</label>
                                <img class="cms-show-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->res->main_img_path; ?>">
                            </div>
                            <div class="cms-image-subsection-container">
                                <div class="cms-upload-box" id="upload_box">
                                    <input type="file" name="main_img_path" id="main_img_path" class="cms-file-input" accept="image/*" onchange="previewMain()">

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
                            <div class="cms-form-subsection">
                                <div>
                                    <h1 class="cms-form-subsection-big-label">Additional Images:</h1>
                                    <a></a>
                                </div>                   
                                <table class="cms-restaurant-images-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Change</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? if($view_model->images != null): ?>
                                            <? for($i = 0; $i < count($view_model->images); $i++): ?>
                                                <tr>
                                                    <th>
                                                        <div class="cms-retaurant-image-container">
                                                            <img src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->images[$i]->path; ?>">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cms-file-image-input" id="<? echo 'upload_' . $i; ?>">
                                                            <input id="<? echo 'image_' . $i; ?>" name="<? echo 'additional_image_' . $i;?>" type="file" onchange="previewImagesFile(<? echo $i; ?>)">
                                                            <input type="hidden" name="<? echo 'additional_image_id_' . $i; ?>" value="<? echo $view_model->images[$i]->image_id; ?>">
                                                            <div id="<? echo 'image_perview_' . $i; ?>" class="cms-file-image-input-preview" style="display: none;">
                                                                <img id="<? echo 'image_perview_image_' . $i; ?>" src="#">
                                                            </div>
                                                            <div id="<? echo 'image_text_' . $i; ?>" class="cms-file-image-input-text">
                                                                <strong>Change image</strong>
                                                                <span>Choose a file</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cms-restaurant-images-actions-container">
                                                            <form action="/cms/yummy/restaurant/images/delete" method="post">
                                                                <input type="hidden" name="restaurant_id" value="<? echo $view_model->res->restaurant_id; ?>">
                                                                <input type="hidden" name="image_id" value="<? echo $view_model->images[$i]->image_id; ?>">

                                                                <button type="submit" class="cms-restaurant-images-delete-button">Delete</button>
                                                            </form>
                                                        </div>
                                                    </th>
                                                </tr>
                                            <? endfor; ?>
                                        <? endif; ?>

                                        <? if($view_model->images == null || count($view_model->images) < 10): ?>
                                            <form action="/cms/yummy/restaurant/image" enctype="multipart/form-data" method="post">
                                                <input type="hidden" name="restaurant_id" value="<? echo $view_model->res->restaurant_id; ?>">
                                                <tr>
                                                    <th class="cms-add-new-image"><span>New image</span></th>
                                                    <th>
                                                        <div class="cms-file-image-input" id="upload_add">
                                                            <input id="image_add" name="image_add" type="file" onchange="previewAddFile()">
                                                            <div id="image_perview_add" class="cms-file-image-input-preview" style="display: none;">
                                                                <img id="image_perview_image_add" src="#">
                                                            </div>
                                                            <div id="image_text_add" class="cms-file-image-input-text">
                                                                <strong>Change image</strong>
                                                                <span>Choose a file</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cms-restaurant-images-actions-container">
                                                            <button class="cms-restaurant-images-add-button" onclick="mainSubmitButtonClick()">Add</button>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </form>
                                        <? endif; ?>
                                    </tbody>
                                </table>
                            </div> 
                            
                            <div class="cms-form-subsection">
                                <div>
                                    <h1 class="cms-form-subsection-big-label">Tags:</h1>
                                    <a></a>
                                </div>                   
                                <table class="cms-restaurant-images-table">
                                    <thead>
                                        <tr>
                                            <th>Tag</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? if($view_model->types != null): ?>
                                            <? for($i = 0; $i < count($view_model->types); $i++): ?>
                                                <form action="/cms/yummy/restaurant/tag/delete" enctype="multipart/form-data" method="post">
                                                    <input type="hidden" name="restaurant_id" value="<? echo $view_model->res->restaurant_id; ?>">
                                                    <input type="hidden" name="type_id" value="<? echo $view_model->types[$i]->type_id; ?>">
                                                    <tr>
                                                        <th><? echo $view_model->types[$i]->name; ?></th>
                                                        <th><button type="submit" class="cms-restaurant-images-delete-button">Delete</button></th>
                                                    </tr>
                                                </form>
                                            <? endfor; ?>
                                        <? endif; ?>

                                        <form action="/cms/yummy/restaurant/tag" enctype="multipart/form-data" method="post">
                                            <input type="hidden" name="restaurant_id" value="<? echo $view_model->res->restaurant_id; ?>">
                                            <input type="hidden" name="tag_id" id="new_tag_input" value="-1">
                                            <tr>
                                                <th>
                                                    <select onchange="newTagSelectValueChanged(this)">
                                                        <option value="" disabled selected>Select tag</option>
                                                        <? foreach($view_model->all_types as $tag): ?>
                                                            <option value="<? echo $tag->type_id; ?>"><? echo $tag->name; ?></option>
                                                        <? endforeach; ?>
                                                    </select>
                                                </th>
                                                <th><button type="submit" class="cms-restaurant-images-add-button">Add</button></th>
                                            </tr>
                                        </form>
                                    </tbody>
                                </table>
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

    
    let addintional_image_count = <? echo count($view_model->images); ?>;

    if(addintional_image_count > 0){
        let images_preview = []; // List of is preview images is shown for images list

        for (let i = 0; i < addintional_image_count; i++) {
            images_preview[i] = false; 

            initImageInput('upload_' + i, 'image_' + i, function () { previewImagesFile(i); }); // Init change image input for additional images
        } 

        if(addintional_image_count < 10){
            let add_preview = false;

            initImageInput('upload_add', 'image_add',  previewAddFile); // Init add new image
        }
    }   

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

    // Update preview image of one of additional images
    function previewImagesFile(id) {
        preview('image_perview_image_' + id, 'image_' + id, 'image_perview_' + id, 'image_text_' + id);
    }

    // Update preview image of add new image
    function previewAddFile() {
        preview('image_perview_image_add', 'image_add', 'image_perview_add', 'image_text_add');
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

    function googleUriButtonClick(){
        window.open('https://www.google.com/maps?q=' + document.getElementById('address_uri').value);
    }

    function websiteLinkButtonClick(){
        window.open(document.getElementById('website_link').value);
    }

    function activeSelectValueChanged(caller){
        document.getElementById("active").value = caller.value;
    }

    function costRatingSelectValueChanged(caller){
        document.getElementById("cost_rating").value = caller.value;
    }

    function newTagSelectValueChanged(caller){
        document.getElementById("new_tag_input").value = caller.value;
    }

    function mainSubmitButtonClick(){
        document.getElementById('main_form').submit();
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>