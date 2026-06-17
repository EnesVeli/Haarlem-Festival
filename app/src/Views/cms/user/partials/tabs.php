<? if(isset($view_model)): ?>
    <div class="cms-tabs-container">
        <a class="cms-tab <?= $view_model->topper->active_tab === 0 ? 'cms-tab-active' : '' ?>" href="/cms/user">List</a>
        <a class="cms-tab <?= $view_model->topper->active_tab === 1 ? 'cms-tab-active' : '' ?>" href="/cms/user/add">Add User</a>
    </div>
<? else: ?>
    <div class="cms-tabs-container">
        <a class="cms-tab" href="/cms/user">List</a>
        <a class="cms-tab" href="/cms/user/add">Add User</a>
    </div>  
<? endif; ?>