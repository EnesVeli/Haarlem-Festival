<? if(isset($view_model)): ?>
    <div class="cms-topper">
        <div class="cms-topper-container">
            <span class="cms-topper-admin-label">ADMIN PANEL</span>
            <h1 class="cms-topper-title"><?= htmlspecialchars($view_model->topper->title) ?></h1>

            <?php if (!empty($view_model->topper->subtitle)): ?>
                <p class="cms-topper-subtitle"><?= htmlspecialchars($view_model->topper->subtitle) ?></p>
            <?php endif; ?>
        </div>
    </div>
<? else: ?>
    <div class="cms-topper">
        <div class="cms-topper-container">
            <span class="cms-topper-admin-label">ADMIN PANEL</span>
            <h1 class="cms-topper-title">Users CMS</h1>
            <p class="cms-topper-subtitle">Something went wrong.</p>
        </div>
    </div>
<? endif; ?>