<?php
/** @var  \App\ViewModels\Yummy\Cms\YummyAddViewModel $view_model */
/** @var ?string $error_message */
/** @var ?string $success_message */

$pageTitle = 'Yummy CMS - New Restaurant';
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

            <form id="main_form" action="/cms/yummy/restaurant/add" enctype="multipart/form-data" method="post">
                <div class="cms-main-subsection-container">
                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Name:</label>
                        <input id="name" name="name" type="text" class="cms-text-input" maxlength="64" placeholder="Restaurant Name">
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Active:</label>
                        <input id="active" name="active" type="hidden" value="1">
                        <select id="active_sel" class="cms-dropdown-input" onchange="activeSelectValueChanged(this)">
                            <option value="1" selected>Yes</option>
                            <option value="0" style="color: red;">No</option>
                        </select>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Rating:</label>
                        <input id="rating" name="rating" type="number" class="cms-number-input" step="0.1" min="0" max="5" placeholder="3.7">
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Cost Rating:</label>
                        <input id="cost_rating" name="cost_rating" type="hidden" value="2">
                        <select id="cost_rating_sel" class="cms-dropdown-input" onchange="costRatingSelectValueChanged(this)">
                            <option value="1">€</option>
                            <option value="2" selected>€€</option>
                            <option value="3">€€€</option>
                        </select>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Mini Text:</label>
                        <textarea id="mini_text" name="mini_text" class="cms-text-text-area text-mid" maxlength="256" placeholder="Small description of the restaurant"></textarea>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Text:</label>
                        <textarea id="text" name="text" class="cms-text-text-area text-long" maxlength="2048" placeholder="Long description of the restaurant"></textarea>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Address:</label>
                        <input id="address_text" name="address_text" type="text" class="cms-text-input" maxlength="128" placeholder="Street-Name 11, 1111 AA Haarlem">
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Address Google Uri:</label>
                        <div class="cms-uri-input-container">
                            <input id="address_uri" name="address_uri" type="text" maxlength="256" placeholder="Ratatouille+Food+%26+Wine">
                            <button type="button" onclick="googleUriButtonClick()">View</button>
                        </div>            
                        <div>*It should be part of uri in google maps: https://www.google.com/maps/place/<strong>Ratatouille+Food+%26+Wine</strong>/...*</div>
                    </div>

                    <div class="cms-form-subsection">
                        <label class="cms-form-label">Website Link:</label>
                        <div class="cms-uri-input-container">
                            <input id="website_link" name="website_link" type="text" maxlength="256" placeholder="http://...">
                            <button type="button" onclick="websiteLinkButtonClick()">View</button>
                        </div>           
                    </div>

                    <div class="cms-form-subsection">
                        <h1 class="cms-form-subsection-big-label">Opening Hours</h1>
                        <div class="cms-opening-hours-container">
                            <div>
                                <label>Monday:</label>
                                <input id="opening_hours_monday" name="opening_hours_monday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                            <div>
                                <label>Tuesday:</label>
                                <input id="opening_hours_tuesday" name="opening_hours_tuesday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                            <div>
                                <label>Wednesday:</label>
                                <input id="opening_hours_wednesday" name="opening_hours_wednesday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                            <div>
                                <label>Thursday:</label>
                                <input id="opening_hours_thursday" name="opening_hours_thursday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                            <div>
                                <label>Friday:</label>
                                <input id="opening_hours_friday" name="opening_hours_friday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                            <div>
                                <label>Saturday:</label>
                                <input id="opening_hours_saturday" name="opening_hours_saturday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                            <div>
                                <label>Sunday:</label>
                                <input id="opening_hours_sunday" name="opening_hours_sunday" type="text" maxlength="64" placeholder="11:00 - 18:00">
                            </div>
                        </div>
                    </div>

                    <label class="cms-form-label">Main Image:</label>
                    <div class="cms-form-subsection-row">
                        <div class="cms-image-subsection-container-alone">
                            <div class="cms-upload-box" id="upload_box">
                                <input type="file" name="main_img_path" id="main_img_path" class="cms-file-input" accept="image/*" onchange="previewMain()">

                                <div class="cms-upload-inner">
                                    <div id="img_none">
                                        <strong>Set main image</strong>
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
                        <h3>Time Slots</h3>
                        <div class="cms-add-button-table-container">
                            <button class="cms-add-button-remove" id="remove" type="button" onclick="recalcTable(0)" disabled>Remove</button>
                            <button class="cms-add-button-add" id="add" type="button" onclick="recalcTable(1)">Add</button>
                        </div>
                        <input type="hidden" id="slot_number" name="slot_number" value="1">
                        <div class="cms-add-time-slot-table-container">
                            <table class="cms-add-time-slot-table">
                                <thead>
                                    <tr class="cms-add-time-slot-table-top">
                                        <th><div>Time</div></th>
                                        <th><div>Duration</div></th>
                                        <th><div>Capacity</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? for ($i = 0; $i < 10; $i++): ?>
                                        <tr class="cms-add-time-slot-table-tr" id="<? echo 'tr_' . $i; ?>">
                                            <th>
                                                <div id="<? echo 'a_' . $i; ?>" class="cms-add-time-container">
                                                    <input type="hidden" id="<? echo 'slot_time_hour_' . $i; ?>" name="<? echo 'slot_time_hour_' . $i; ?>">
                                                    <select id="<? echo 'time_hour_' . $i; ?>" class="cms-dropdown-input" onchange="slotTimeHourSelectValueChanged(<? echo $i; ?>)">
                                                        <? for($j = 0; $j < 25; $j++): ?>
                                                            <option value="<? echo $j; ?>"><? echo ($j < 10 ? '0' . $j : $j); ?></option>
                                                        <? endfor; ?>
                                                    </select>
                                                    <input type="hidden" id="<? echo 'slot_time_min_' . $i; ?>" name="<? echo 'slot_time_min_' . $i; ?>">
                                                    <select id="<? echo 'time_min_' . $i; ?>" class="cms-dropdown-input" onchange="slotTimeMinSelectValueChanged(<? echo $i; ?>)">
                                                        <? for($j = 0; $j < 65; $j += 5): ?>
                                                            <option value="<? echo $j; ?>"><? echo ($j < 10 ? '0' . $j : $j); ?></option>
                                                        <? endfor; ?>
                                                    </select>
                                                </div>
                                            </th>
                                            <th>
                                                <div id="<? echo 'b_' . $i; ?>">
                                                    <input type="hidden" id="<? echo 'slot_duration_' . $i; ?>" name="<? echo 'slot_duration_' . $i; ?>">
                                                    <select id="<? echo 'time_dur_' . $i; ?>" class="cms-dropdown-input" onchange="slotDurationSelectValueChanged(<? echo $i; ?>)">
                                                        <? $max_duration_hours = 4; ?>
                                                        <? for($j = 0; $j < $max_duration_hours; $j++): ?>
                                                            <? for($k = 0; $k < 60; $k += 5): ?>
                                                                <option value="<? echo ($k + $j * 60); ?>"><? echo '0' . $j . ':' . ($k < 10 ? '0' . $k : $k); ?></option>
                                                            <? endfor; ?>
                                                        <? endfor; ?>

                                                        <option value="<? echo ($max_duration_hours * 60); ?>"><? echo '0' . $max_duration_hours . ':00'; ?></option>
                                                    </select>
                                                </div>
                                            </th>
                                            <th>
                                                <div id="<? echo 'c_' . $i; ?>">
                                                    <input class="cms-number-input" type="number" name="<? echo 'slot_capacity_' . $i; ?>" step="5" min="5" max="250" value="5">
                                                </div>
                                            </th>
                                        </tr>
                                    <? endfor; ?>
                                </tbody>
                            </table>      
                        </div>        
                    </div>

                    <button class="cms-submit-button" type="submit">Add</button>
                </div>
            </form>      
        </div>
    </div>
</div>

<script type="text/javascript">
    let show_preview = false; // Is preview image of main image displayed.

    initImageInput('upload_box', 'main_img_path', previewMain) // Init change image input for main image

    
    const def_display = 'flex';
    let last_tabel_column = 0;

    initTable();


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
                document.getElementById(preview_image).style = "display: " + def_display + ";";

                show_preview = true;
            }
        } else {
            if(show_preview){
                document.getElementById(preview_text).style = "display: " + def_display + ";";
                document.getElementById(preview_image).style = "display: none;";

                show_preview = false;
            }

            preview.src = "";
        }
    }

    function recalcTable(change){
        if(change == 1){
            if(last_tabel_column == 9) return;

            document.getElementById('tr_' + (last_tabel_column + 1)).style = "display: " + def_display + ";";  

            last_tabel_column++;

            if(last_tabel_column == 9) document.getElementById('add').disabled = true;
            document.getElementById('remove').disabled = false;
        }
        else{
            if(last_tabel_column == 0) return;

            document.getElementById('tr_' + (last_tabel_column)).style = "display: none;";

            last_tabel_column--;

            if(last_tabel_column == 0) document.getElementById('remove').disabled = true;
            document.getElementById('add').disabled = false;
        }

        document.getElementById('slot_number').value = last_tabel_column + 1;
    }

    function initTable(){
        for (let i = 1; i < 10; i++) {
            document.getElementById('tr_' + i).style = 'display: none;';
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

    function slotTimeHourSelectValueChanged(i){
        document.getElementById("slot_time_hour_" + i).value = document.getElementById("time_hour_" + i).value;
    }

    function slotTimeMinSelectValueChanged(i){
        document.getElementById("slot_time_min_" + i).value = document.getElementById("time_min_" + i).value;
    }

    function slotDurationSelectValueChanged(i){
        document.getElementById("slot_duration_" + i).value = document.getElementById("time_dur_" + i).value;
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>