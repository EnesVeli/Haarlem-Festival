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

            <?php if(!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <? if(isset($view_model)): ?>     
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
                                        <th><? echo htmlspecialchars($res->name); ?></th>
                                        <th><? echo htmlspecialchars($res->mini_text); ?></th>
                                        <th><? echo $res->getRatingFormated(); ?></th>
                                        <th><? echo $res->getCostRatingString(); ?></th>
                                        <th <? echo $res->active ? '' : 'class="cms-res-list-active-no"'; ?>><? echo $res->active == true ? 'Yes' : 'No'; ?></th>
                                        <th><a class="cms-res-list-view-button" href="<? echo '/cms/yummy/restaurant?id=' . $res->restaurant_id; ?>">View</a></th>
                                    </tr>
                                <? endforeach; ?>
                            <? endif; ?>
                        </tbody>
                    </table>
                </div>

                <? if($view_model->page_number > 1): ?>   
                    <div class="cms-res-list-line"></div>               
                    <div class="cms-res-list-pages-container">
                        <? if($view_model->cur_page != 0):?>
                            <button class="cms-res-list-page-button cms-res-list-page-button-unsel" onclick="pageButtonClick(0)">&lt;&lt;</button>
                            <button class="cms-res-list-page-button cms-res-list-page-button-unsel" onclick="previousPageClick()">&lt;</button>
                        <? endif; ?>

                        <? for($i = $view_model->cur_page - $view_model->page_offset; $i < $view_model->cur_page + $view_model->page_limit; $i++):?>
                            <button id="page-<? echo $i?>" class="cms-res-list-page-button cms-res-list-page-button-unsel" onclick="pageButtonClick(<? echo $i;?>)"><? echo $i + 1; ?></button>
                        <? endfor; ?>
                        
                        <? if($view_model->cur_page != $view_model->page_number - 1):?>
                            <button class="cms-res-list-page-button cms-res-list-page-button-unsel" onclick="nextPageClick()">&gt;</button>
                            <button class="cms-res-list-page-button cms-res-list-page-button-unsel" onclick="pageButtonClick(<? echo $view_model->page_number - 1;?>)">&gt;&gt;</button>
                        <? endif; ?>
                    </div>
                <? endif; ?>
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

    // Select curring sorting method
    document.getElementById('field_' + sort_field).className = 'cms-res-list-selected-sort-field cms-res-list-sort-field';
    document.getElementById('field_' + sort_field + '_' + (sort_order == 0 ? 'asc' : 'desc')).style.display = 'flex';

    // Select current page
    if(page_number > 1) document.getElementById("page-" + cur_page).className = "cms-res-list-page-button cms-res-list-page-button-sel";

    function sortingOptionClick(sender){
        let uri = '/cms/yummy/restaurant-list?'; // Set base for uri

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

    function pageButtonClick(page = 0){
        if(cur_page == page) return;

        let uri = '/cms/yummy/restaurant-list?sort='; // Set base for uri

        uri += sort_field + '&order=' + sort_order + '&page=' + page;

        window.location.href = uri; 
    }

    function previousPageClick(){
        if(current_page > 0){
            reloadFilterSortPage(current_page - 1);
        }
    }

    function nextPageClick(){
        if(current_page < last_page - 1){
            reloadFilterSortPage(current_page + 1);
        }
    }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>