<?php
/** @var \App\ViewModels\HomeEditViewModel $viewModel */
$pageTitle = $viewModel->pageTitle;
$pageCSS = 'cms-home-history.css';
require __DIR__ . '/../../partials/header.php';
?>

<div class="cms-page container-fluid py-4">

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="cms-title-row">
        <div>
            <p class="cms-eyebrow">Content management</p>
            <h1>Edit homepage</h1>
        </div>
        <a href="/" target="_blank" class="btn btn-outline-secondary">Preview site</a>
    </div>

    <div class="card cms-card mb-5">
        <div class="card-header fw-semibold">Hero &amp; Program Section</div>
        <div class="card-body">
            <form method="POST" action="/cms/home/save-content" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title" class="form-control"
                               value="<?= $viewModel->get('hero_title', 'THE FESTIVAL') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" class="form-control"
                               value="<?= $viewModel->get('hero_subtitle', '5 Events • 4 Days • One Haarlem') ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Hero Description</label>
                        <textarea name="hero_description" class="form-control" rows="3"><?= $viewModel->get('hero_description') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hero Background Image</label>
                        <input type="file" name="hero_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">Upload JPG, PNG or WEBP image. Max size 5 MB.</div>
                        <input type="hidden" name="existing_hero_image" value="<?= htmlspecialchars($viewModel->content['hero_image'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <?php if (!empty($viewModel->content['hero_image'])): ?>
                        <label class="form-label">Current Hero Image</label><br>
                        <img src="/assets/uploads/History/<?= htmlspecialchars($viewModel->content['hero_image']) ?>"
                             alt="Hero" style="max-height:120px; border-radius:6px; object-fit:cover;">
                        <?php endif; ?>
                    </div>

                    <hr class="col-12">

                    <div class="col-md-6">
                        <label class="form-label">Program Section Title</label>
                        <input type="text" name="program_title" class="form-control"
                               value="<?= $viewModel->get('program_title', 'What Is My Program?') ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Program Section Description</label>
                        <textarea name="program_description" class="form-control" rows="3"><?= $viewModel->get('program_description') ?></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Content</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Event Cards</h2>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#eventModal" data-id="">
            Add event card
        </button>
    </div>

    <div class="table-responsive card cms-card">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>URL</th>
                    <th>Order</th>
                    <th>Active</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($viewModel->eventCards as $card): ?>
                <tr>
                    <td><?= (int)$card['id'] ?></td>
                    <td><?= htmlspecialchars($card['title']) ?></td>
                    <td><?= htmlspecialchars($card['category']) ?></td>
                    <td><a href="<?= htmlspecialchars($card['url']) ?>" target="_blank"><?= htmlspecialchars($card['url']) ?></a></td>
                    <td><?= (int)$card['sort_order'] ?></td>
                    <td>
                        <?php if ($card['is_active']): ?>
                            <span class="badge bg-success">Yes</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">No</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#eventModal"
                                data-id="<?= (int)$card['id'] ?>"
                                data-title="<?= htmlspecialchars($card['title']) ?>"
                                data-category="<?= htmlspecialchars($card['category']) ?>"
                                data-short="<?= htmlspecialchars($card['short_description'] ?? '') ?>"
                                data-long="<?= htmlspecialchars($card['long_description'] ?? '') ?>"
                                data-venues="<?= htmlspecialchars($card['venues'] ?? '') ?>"
                                data-url="<?= htmlspecialchars($card['url']) ?>"
                                data-button-label="<?= htmlspecialchars($card['button_label'] ?? '') ?>"
                                data-icon="<?= htmlspecialchars($card['icon'] ?? '') ?>"
                                data-bg-class="<?= htmlspecialchars($card['bg_class'] ?? '') ?>"
                                data-image="<?= htmlspecialchars($card['image'] ?? '') ?>"
                                data-sort-order="<?= (int)$card['sort_order'] ?>"
                                data-is-active="<?= (int)$card['is_active'] ?>">
                            Edit
                        </button>
                        <form method="POST" action="/cms/home/delete-event" class="d-inline"
                              onsubmit="return confirm('Delete this event card?')">
                            <input type="hidden" name="id" value="<?= (int)$card['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($viewModel->eventCards)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No event cards yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="/cms/home/save-event" enctype="multipart/form-data">
                <input type="hidden" name="id" id="modal-id">
                <input type="hidden" name="existing_image" id="modal-existing-image">

                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="modal-title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" id="modal-category" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" id="modal-short" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" id="modal-long" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Venues</label>
                            <input type="text" name="venues" id="modal-venues" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL / Link</label>
                            <input type="text" name="url" id="modal-url" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Label</label>
                            <input type="text" name="button_label" id="modal-button-label" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bootstrap Icon class</label>
                            <input type="text" name="icon" id="modal-icon" class="form-control" placeholder="bi-music-note">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Header BG class</label>
                            <input type="text" name="bg_class" id="modal-bg-class" class="form-control" placeholder="bg-primary">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" id="modal-sort-order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Card Image (optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">Upload JPG, PNG or WEBP image. Max size 5 MB.</div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="modal-is-active" value="1">
                                <label class="form-check-label" for="modal-is-active">Active (visible on website)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Event Card</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('eventModal').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    if (!btn) return;

    const id = btn.getAttribute('data-id') || '';
    document.getElementById('modal-id').value             = id;
    document.getElementById('modal-title').value          = btn.getAttribute('data-title')        || '';
    document.getElementById('modal-category').value       = btn.getAttribute('data-category')     || '';
    document.getElementById('modal-short').value          = btn.getAttribute('data-short')        || '';
    document.getElementById('modal-long').value           = btn.getAttribute('data-long')         || '';
    document.getElementById('modal-venues').value         = btn.getAttribute('data-venues')       || '';
    document.getElementById('modal-url').value            = btn.getAttribute('data-url')          || '';
    document.getElementById('modal-button-label').value   = btn.getAttribute('data-button-label') || '';
    document.getElementById('modal-icon').value           = btn.getAttribute('data-icon')         || '';
    document.getElementById('modal-bg-class').value       = btn.getAttribute('data-bg-class')     || '';
    document.getElementById('modal-sort-order').value     = btn.getAttribute('data-sort-order')   || '0';
    document.getElementById('modal-existing-image').value = btn.getAttribute('data-image')        || '';

    const isActive = btn.getAttribute('data-is-active') === '1';
    document.getElementById('modal-is-active').checked = isActive;

    const existingImage = btn.getAttribute('data-image') || '';
    document.getElementById('modal-image-hint').textContent =
        existingImage ? 'Current: ' + existingImage + ' (upload new to replace)' : '';

    document.getElementById('eventModalLabel').textContent = id ? 'Edit Event Card' : 'Add Event Card';
});
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
