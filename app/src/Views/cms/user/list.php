<?php
/** @var  \App\ViewModels\Yummy\Cms\YummyHomeViewModel $view_model */
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
        <? else: ?>
        <? endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>