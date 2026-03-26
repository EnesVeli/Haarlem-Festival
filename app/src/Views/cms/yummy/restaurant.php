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
            
            <div class="cms-res-list-table-container">
                <table class="cms-res-list-table">
                    <thead>
                        <tr>
                            <th>Image</th>            
                            <th id="field_0" class="cms-res-list-sort-field" sort="0" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Name<div id="field_0_asc">&nbsp;↑</div><div id="field_0_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_1" class="cms-res-list-sort-field" sort="1" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Mini Text<div id="field_1_asc">&nbsp;↑</div><div id="field_1_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_2" class="cms-res-list-sort-field" sort="2" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Rating<div id="field_2_asc">&nbsp;↑</div><div id="field_2_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_3" class="cms-res-list-sort-field" sort="3" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Cost<div id="field_3_asc">&nbsp;↑</div><div id="field_3_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_4" class="cms-res-list-sort-field" sort="4" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Active<div id="field_4_asc">&nbsp;↑</div><div id="field_4_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? if (count($view_model->restaurants) == 0): ?>
                            <tr>
                                <td colspan="7">No restaurants found.</td>
                            </tr>
                        <? else: ?>
                            <? foreach($view_model->restaurants as $res): ?>
                                <tr>
                                    <th>
                                        <div class="cms-res-list-image-container">
                                            <img class="cms-res-list-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $res->main_img_path ?>">
                                        </div>
                                    </th>
                                    <th><? echo $res->name; ?></th>
                                    <th><? echo $res->mini_text; ?></th>
                                    <th><? echo $res->getRatingFormated(); ?></th>
                                    <th><? echo $res->getCostRatingString(); ?></th>
                                    <th><? echo $res->active == true ? 'Yes' : 'No'; ?></th>
                                    <th><a>View</a></th>
                                </tr>
                            <? endforeach; ?>
                        <? endif; ?>
                    </tbody>
                </table>
            </div>

            <? if(count($view_model->restaurants) > 0): ?>
                <div>
                    <div>
                        
                    </div>
                </div>
            <? endif; ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    // Get dat from view model
    let sort_field = <? echo $view_model->sort_field; ?>;
    let sort_order = <? echo $view_model->sort_order; ?>;

    let cur_page = <? echo $view_model->cur_page; ?>;
    let page_number = <? echo $view_model->page_number; ?>;

    //Set up sorting 
    for (let i = 0; i < 5; i++) {
        document.getElementById('field_' + i + '_asc').style.display = 'none';
        document.getElementById('field_' + i + '_desc').style.display = 'none';
    }

    document.getElementById('field_' + sort_field).className = 'cms-res-list-selected-sort-field cms-res-list-sort-field';
    document.getElementById('field_' + sort_field + '_' + (sort_order == 0 ? 'asc' : 'desc')).style.display = 'flex';

    function sortingOptionClick(sender){
        let uri = '/cms/yummy/restaurant?'; // Set base for uri

        let sorting = sender.getAttribute("sort");

        uri += 'sort=' + sorting;
        
        if(sort_field == sorting){
            uri += '&order=' + (sort_order == 0 ? 1 : 0); 
        }
        else{
            uri += '&order=0'; 
        }

        uri += '&page=0'; 

        window.location.href = uri; 
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>