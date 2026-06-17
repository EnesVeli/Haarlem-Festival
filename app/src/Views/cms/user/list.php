<?php
/** @var \App\ViewModels\User\UserListViewModel $view_model */
/** @var ?string $error_message */
/** @var ?string $success_message */

$pageTitle = 'Users Cms - List';
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
        </div>

        <? if(isset($view_model)): ?>
            <div class="cms-res-list-table-container">
                <table class="cms-res-list-table">
                    <thead>
                        <tr>
                            <th>Profile Image</th>            
                            <th id="field_0" class="cms-res-list-sort-field" sort="0" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Name<div id="field_0_asc">&nbsp;↑</div><div id="field_0_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_1" class="cms-res-list-sort-field" sort="1" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Email<div id="field_1_asc">&nbsp;↑</div><div id="field_1_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_2" class="cms-res-list-sort-field" sort="2" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Role<div id="field_2_asc">&nbsp;↑</div><div id="field_2_desc">&nbsp;↓</div>
                                </div>
                            </th>
                            <th id="field_3" class="cms-res-list-sort-field" sort="3" onclick="sortingOptionClick(this)">
                                <div class="cms-res-list-sort-container">
                                    Registration Date<div id="field_3_asc">&nbsp;↑</div><div id="field_3_desc">&nbsp;↓</div>
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
                        <? if (count($view_model->users) == 0): ?>
                            <tr>
                                <td colspan="7">No restaurants found.</td>
                            </tr>
                        <? else: ?>
                            <? foreach($view_model->users as $user): ?>
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
        <? else: ?>
        <? endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>