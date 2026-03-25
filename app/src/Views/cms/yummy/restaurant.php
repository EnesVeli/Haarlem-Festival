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
            
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Mini Text</th>
                        <th>Rating</th>
                        <th>Cost</th>
                        <th>Active</th>
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
                                <th><img src="<? echo '/assets/uploads/yummy/restaurants/' . $res->main_img_path ?>"></th>
                                <th><? echo $res->name; ?></th>
                                <th><? echo $res->mini_text; ?></th>
                                <th><? echo $res->rating; ?></th>
                                <th><? echo $res->cost_rating; ?></th>
                                <th><? echo $res->active == true ? 'Yes' : 'No'; ?></th>
                                <th></th>
                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>