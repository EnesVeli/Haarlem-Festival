<?php
/** @var string $activeTab */
$activeTab = $activeTab ?? '';
?>

<div class="jazz-cms-tabs">
    <a class="jazz-cms-tab <?= $activeTab === 'dashboard' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/home">
        Dashboard
    </a>

    <a class="jazz-cms-tab <?= $activeTab === 'hero' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/hero">
        Hero
    </a>

    <a class="jazz-cms-tab <?= $activeTab === 'intro' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/intro">
        Intro
    </a>

    <a class="jazz-cms-tab <?= $activeTab === 'experiences' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/experiences">
        Experiences
    </a>

    <a class="jazz-cms-tab <?= $activeTab === 'performers' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/performers">
        Performers
    </a>

    <a class="jazz-cms-tab <?= $activeTab === 'recommendations' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/recommendations">
        Recommendations
    </a>

    <a class="jazz-cms-tab <?= $activeTab === 'locations' ? 'jazz-cms-tab-active' : '' ?>" href="/cms/jazz/locations">
        Locations
    </a>
</div>