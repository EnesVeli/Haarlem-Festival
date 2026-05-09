<?php
/** @var \App\ViewModels\Jazz\JazzCmsViewModels\JazzDashboardCmsViewModel $vm */

$pageTitle = 'Jazz CMS Dashboard';
$pageCSS = 'jazz.css';
$user = $vm->currentUser ?? null;

require __DIR__ . '/../../partials/header.php';

?>

<div class="container py-4 jazz-cms-page">

<?php
$title = 'Jazz CMS Dashboard';
$subtitle = 'Manage all Jazz content in one place.';
$buttonText = 'Preview Page';
$buttonLink = '/jazz';

require __DIR__ . '/partials/cmsHero.php';
?>

<div class="jazz-cms-panel">

    <?php
    $activeTab = 'dashboard';
    require __DIR__ . '/partials/tabs.php';
    ?>

    <div class="jazz-cms-section">
        <div class="jazz-cms-welcome-card">
            <h2 class="jazz-cms-section-title">Welcome</h2>
            <p class="jazz-cms-text">
                Choose a section above to manage Jazz homepage and performer detail content.
            </p>
        </div>
    </div>

</div>

</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>