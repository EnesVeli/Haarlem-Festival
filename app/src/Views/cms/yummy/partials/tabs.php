<? if(isset($view_model)): ?>
    <div class="cms-tabs-container">
        <a class="cms-tab <?= $view_model->topper->active_tab == 0 ? 'cms-tab-active' : '' ?>" href="/cms/yummy/">Home</a>
        <a class="cms-tab <?= $view_model->topper->active_tab == 1 ? 'cms-tab-active' : '' ?>" href="/cms/yummy/list">List</a>
        <a class="cms-tab <?= $view_model->topper->active_tab == 2 ? 'cms-tab-active' : '' ?>" href="/cms/yummy/restaurant-list">Restaurants</a>
    </div>
<? else: ?>
    <div class="cms-tabs-container">
        <a class="cms-tab" href="/cms/yummy/">Home</a>
        <a class="cms-tab" href="/cms/yummy/list">List</a>
        <a class="cms-tab" href="/cms/yummy/restaurant-list">Restaurants</a>
    </div>  
<? endif; ?>