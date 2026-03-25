
<div class="cms-tabs-container">
    <a class="cms-tab <?= $view_model->topper->active_tab == 0 ? 'cms-tab-active' : '' ?>" href="/cms/yummy/">Home</a>
    <a class="cms-tab <?= $view_model->topper->active_tab == 1 ? 'cms-tab-active' : '' ?>" href="/cms/yummy/list">List</a>
    <a class="cms-tab <?= $view_model->topper->active_tab == 2 ? 'cms-tab-active' : '' ?>" href="/cms/yummy/restaurant">Restaurants</a>
    <a class="cms-tab <?= $view_model->topper->active_tab == 3 ? 'cms-tab-active' : '' ?>" href="/cms/jazz/experiences">Experiences</a>
    <a class="cms-tab <?= $view_model->topper->active_tab == 4 ? 'cms-tab-active' : '' ?>" href="/cms/jazz/performers">Performers</a>
    <a class="cms-tab <?= $view_model->topper->active_tab == 5 ? 'cms-tab-active' : '' ?>" href="/cms/jazz/recommendations">Recommendations</a>
    <a class="cms-tab <?= $view_model->topper->active_tab == 6 ? 'cms-tab-active' : '' ?>" href="/cms/jazz/locations">Locations</a>
</div>