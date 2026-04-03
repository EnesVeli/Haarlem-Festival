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
                <div class="cms-main-subsection-container">
                    <input id="restaurant_id" name="restaurant_id" type="hidden" value="<? echo $view_model->res->restaurant_id; ?>">

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Name:</label>
                        <input id="name" name="name" type="text" class="cms-text-input" value="<? echo htmlspecialchars($view_model->res->name) ?>">
                    </div>

                     <div class="cms-form-subsection">
                        <label class="cms-form-label">Active:</label>
                        <select id="active" class="cms-dropdown-input">
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
                        <select id="cost_rating" class="cms-dropdown-input">
                            <option value="1" <? echo ($view_model->res->cost_rating == 1 ? 'selected' : ''); ?>>€</option>
                            <option value="2" <? echo ($view_model->res->cost_rating == 2 ? 'selected' : ''); ?>>€€</option>
                            <option value="3" <? echo ($view_model->res->cost_rating == 3 ? 'selected' : ''); ?>>€€€</option>
                        </select>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Mini Text:</label>
                        <textarea id="mini_text" name="mini_text" class="cms-text-text-area text-mid"><? echo htmlspecialchars($view_model->res->mini_text) ?></textarea>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Text:</label>
                        <textarea id="text" name="text" class="cms-text-text-area text-long"><? echo htmlspecialchars($view_model->res->text) ?></textarea>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Address:</label>
                        <input id="address_text" name="address_text" type="text" class="cms-text-input" value="<? echo htmlspecialchars($view_model->res->address_text) ?>">
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Address Google Uri:</label>
                        <div class="cms-uri-input-container">
                            <input id="address_uri" name="address_uri" type="text" value="<? echo htmlspecialchars($view_model->res->address_uri) ?>">
                            <button onclick="googleUriButtonClick()">View</button>
                        </div>            
                        <div>*It should be part of uri in google maps: https://www.google.com/maps/place/<strong>Ratatouille+Food+%26+Wine</strong>/...*</div>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Website Link:</label>
                        <div class="cms-uri-input-container">
                            <input id="website_link" name="website_link" type="text" value="<? echo htmlspecialchars($view_model->res->website_link) ?>">
                            <button onclick="websiteLinkButtonClick()">View</button>
                        </div>           
                    </div>

                    <div class="cms-form-subsection">
                        <h1 class="cms-form-subsection-big-label">Opening Hours</h1>
                        <div class="cms-opening-hours-container">
                            <div>
                                <label>Monday:</label>
                                <input id="opening_hours_monday" name="opening_hours_monday" type="text" value="<? echo htmlspecialchars($view_model->hours->monday) ?>">
                            </div>
                            <div>
                                <label>Tuesday:</label>
                                <input id="opening_hours_tuesday" name="opening_hours_tuesday" type="text" value="<? echo htmlspecialchars($view_model->hours->tuesday) ?>">
                            </div>
                            <div>
                                <label>Wednesday:</label>
                                <input id="opening_hours_wednesday" name="opening_hours_wednesday" type="text" value="<? echo htmlspecialchars($view_model->hours->wednesday) ?>">
                            </div>
                            <div>
                                <label>Thursday:</label>
                                <input id="opening_hours_thursday" name="opening_hours_thursday" type="text" value="<? echo htmlspecialchars($view_model->hours->thursday) ?>">
                            </div>
                            <div>
                                <label>Friday:</label>
                                <input id="opening_hours_friday" name="opening_hours_friday" type="text" value="<? echo htmlspecialchars($view_model->hours->friday) ?>">
                            </div>
                            <div>
                                <label>Saturday:</label>
                                <input id="opening_hours_saturday" name="opening_hours_saturday" type="text" value="<? echo htmlspecialchars($view_model->hours->saturday) ?>">
                            </div>
                            <div>
                                <label>Sunday:</label>
                                <input id="opening_hours_sunday" name="opening_hours_sunday" type="text" value="<? echo htmlspecialchars($view_model->hours->sunday) ?>">
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

                    <? if($view_model->images != null): ?>
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
                                    <? for($i = 0; $i < count($view_model->images); $i++): ?>
                                        <tr>
                                            <th>
                                                <div class="cms-retaurant-image-container">
                                                    <img src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->images[$i]->path; ?>">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="cms-file-image-input" id="<? echo 'upload_' . $i; ?>">
                                                    <input id="<? echo 'image_' . $i; ?>" type="file" onchange="previewImagesFile(<? echo $i; ?>)">
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
                                                    <a href="<? echo '/cms/yummy/restaurant/images/delete?id=' . $view_model->images[$i]->image_id; ?>" class="cms-restaurant-images-delete-button">Delete</a>
                                                </div>
                                            </th>
                                        </tr>
                                    <? endfor; ?>
                                    <? if(count($view_model->images) < 12): ?>
                                        <form action="/cms/yummy/restaurant/image" method="post">
                                            <input type="hidden" name="restaurant_id" value="<? echo $view_model->res->restaurant_id;?>">
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
                                                        <button type="submit" class="cms-restaurant-images-add-button">Add</button>
                                                    </div>
                                                </th>
                                            </tr>
                                        </form>
                                    <? endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <? endif; ?>

                    <button class="cms-submit-button" onclick="onSubmitButtonClick()">Save Changes</button>
                </div>
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

        if(addintional_image_count < 12){
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

    function onSubmitButtonClick(){
        if(selected_button == null) return;

        let args = getPostArguments(['restaurant_id','name','active','rating','cost_rating','mini_text','text','address_text','address_uri','website_link',
            'opening_hours_monday','opening_hours_tuesday','opening_hours_wednesday','opening_hours_thursday','opening_hours_friday','opening_hours_saturday','opening_hours_sunday',
            '','','',]);
        
        post('/cms/yummy/restaurant', );
    }

    function getPostArguments(ids){
        let args = {};

        for (let i = 0; i < theArray.length; ++i) {
            args[ids[i]] = document.getElementById(ids[i]).value;
        }

        args['main_img_path'] = document.getElementById('main_img_path').files[0];
    }

    function post(path, params, method='post') {
        const form = document.createElement('form');
        form.method = method;
        form.action = path;

        for (const key in params) {
            if (params.hasOwnProperty(key)) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = params[key];

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function googleUriButtonClick(){
        window.open('https://www.google.com/maps?q=' + document.getElementById('address_uri').value);
    }

    function websiteLinkButtonClick(){
        window.open(document.getElementById('website_link').value);
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>