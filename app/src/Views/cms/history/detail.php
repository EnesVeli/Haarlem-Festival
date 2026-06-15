<?php
/**
 * @var array<string, mixed> $detail
 * @var array<int, array<string, mixed>> $highlights
 * @var array<int, array<string, mixed>> $sections
 * @var array<int, array<string, mixed>> $gallery
 * @var array<int, array<string, mixed>> $facts
 */
$isNew     = empty($detail);
$detailId  = $detail['id'] ?? 0;
$pageTitle = $isNew ? "New Detail Page" : "Edit: " . ($detail['page_title'] ?? '');
$pageCSS = 'cms-home-history.css';
require __DIR__ . '/../../partials/header.php';
?>

<div class="cms-page container-fluid py-4">

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= htmlspecialchars($_SESSION['flash']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?= htmlspecialchars($_SESSION['flash_error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div class="cms-title-row">
    <div>
      <p class="cms-eyebrow">History detail page</p>
      <h1><?= $isNew ? 'New Detail Page' : htmlspecialchars($detail['page_title']) ?></h1>
    </div>
    <a href="/cms/history#tab-details" class="btn btn-outline-secondary">Back to dashboard</a>
  </div>

  <div class="card cms-card mb-4">
    <div class="card-header fw-semibold">Basic Information</div>
    <div class="card-body">
      <form method="POST" action="/cms/history/action" enctype="multipart/form-data">
        <input type="hidden" name="_action" value="save_detail">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
        <input type="hidden" name="id" value="<?= $detailId ?>">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Linked Highlight <span class="text-danger">*</span></label>
            <select name="highlight_id" class="form-select" required>
              <option value="">Select</option>
              <?php foreach ($highlights as $h): ?>
                <option value="<?= $h['id'] ?>"
                  <?= ($detail['highlight_id'] ?? $_GET['highlight_id'] ?? '') == $h['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($h['title']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">URL Slug <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text text-muted">/history/</span>
              <input type="text" name="slug" class="form-control"
                     value="<?= htmlspecialchars($detail['slug'] ?? '') ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Page Title <span class="text-danger">*</span></label>
            <input type="text" name="page_title" class="form-control"
                   value="<?= htmlspecialchars($detail['page_title'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Location</label>
            <input type="text" name="location" class="form-control" placeholder="e.g. Haarlem City Center"
                   value="<?= htmlspecialchars($detail['location'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Founded / Built</label>
            <input type="text" name="founded_year" class="form-control" placeholder="e.g. 1784"
                   value="<?= htmlspecialchars($detail['founded_year'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Style / Type</label>
            <input type="text" name="style_type" class="form-control" placeholder="e.g. Gothic"
                   value="<?= htmlspecialchars($detail['style_type'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Hero Image</label>
            <?php if (!empty($detail['hero_image'])): ?>
              <img src="/assets/uploads/History/<?= htmlspecialchars($detail['hero_image']) ?>"
                   class="d-block img-thumbnail mb-1 cms-preview-img" alt="">
            <?php endif; ?>
            <input type="file" name="hero_image" class="form-control" accept="image/*">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($detail['meta_description'] ?? '') ?></textarea>
          </div>
        </div>

        <button type="submit" class="btn btn-dark mt-3">
          <?= $isNew ? 'Create page' : 'Save changes' ?>
        </button>
      </form>
    </div>
  </div>

  <?php if (!$isNew): ?>

  <div class="card cms-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Content Sections</strong>
      <button class="btn btn-sm btn-light" onclick="openSectionModal()">Add section</button>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr><th>#</th><th>Type</th><th>Title</th><th>Order</th><th style="width:110px"></th></tr>
        </thead>
        <tbody>
          <?php foreach ($sections as $s): ?>
          <tr>
            <td class="text-muted small"><?= $s['id'] ?></td>
            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($s['section_type']) ?></span></td>
            <td><?= htmlspecialchars($s['section_title']) ?></td>
            <td><?= $s['sort_order'] ?></td>
            <td>
              <button class="btn btn-sm btn-outline-primary me-1"
                onclick='openSectionModal(<?= json_encode($s) ?>)'>Edit</button>
              <form method="POST" action="/cms/history/action" class="d-inline"
                    onsubmit="return confirm('Delete section?')">
                <input type="hidden" name="_action" value="delete_section">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
                <input type="hidden" name="detail_id" value="<?= $detailId ?>">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($sections)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">No sections yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card cms-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Photo Gallery</strong>
      <button class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#galleryUpload">Add image</button>
    </div>
    <div class="card-body">

      <div class="collapse mb-3" id="galleryUpload">
        <form method="POST" action="/cms/history/action" enctype="multipart/form-data"
              class="border rounded p_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
          <input type="hidden" name="-3 bg-light">
          <input type="hidden" name="_action" value="add_gallery">
          <input type="hidden" name="detail_id" value="<?= $detailId ?>">
          <div class="row g-2 align-items-end">
            <div class="col-md-5">
              <label class="form-label small fw-semibold">Image <span class="text-danger">*</span></label>
              <input type="file" name="image_path" class="form-control form-control-sm" accept="image/*" required>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Caption</label>
              <input type="text" name="caption" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-semibold">Order</label>
              <input type="number" name="sort_order" class="form-control form-control-sm" value="0" min="0">
            </div>
            <div class="col-md-1">
              <button type="submit" class="btn btn-dark btn-sm w-100">Upload</button>
            </div>
          </div>
        </form>
      </div>

      <div class="d-flex flex-wrap gap-3">
        <?php foreach ($gallery as $img): ?>
          <div class="text-center" style="width:110px">
            <img src="/assets/uploads/History/<?= htmlspecialchars($img['image_path']) ?>"
                 style="width:110px;height:75px;object-fit:cover;border-radius:6px">
            <p class="small text-muted mt-1 mb-1" style="font-size:.7rem">
              <?= htmlspecialchars($img['caption'] ?? '') ?>
            </p>
            <form method="POST" action="/cms/history/action" onsubmit="return confirm('Delete image?')">
              <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
              <input type="hidden" name="_action" value="delete_gallery">
              <input type="hidden" name="id" value="<?= $img['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" style="font-size:.7rem;padding:1px 6px">Delete</button>
            </form>
          </div>
        <?php endforeach; ?>
        <?php if (empty($gallery)): ?>
          <p class="text-muted small">No images yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="card cms-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Quick Facts</strong>
      <button class="btn btn-sm btn-light" onclick="openFactModal()">Add fact</button>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr><th>Icon</th><th>Label</th><th>Value</th><th>Order</th><th style="width:110px"></th></tr>
        </thead>
        <tbody>
          <?php foreach ($facts as $f): ?>
          <tr>
            <td><strong><?= htmlspecialchars($f['icon']) ?></strong></td>
            <td><?= htmlspecialchars($f['label']) ?></td>
            <td><?= htmlspecialchars($f['value']) ?></td>
            <td><?= $f['sort_order'] ?></td>
            <td>
              <button class="btn btn-sm btn-outline-primary me-1"
                onclick='openFactModal(<?= json_encode($f) ?>)'>Edit</button>
              <form method="POST" action="/cms/history/action" class="d-inline"
                    onsubmit="return confirm('Delete fact?')">
                <input type="hidden" name="_action" value="delete_fact">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
                <input type="hidden" name="detail_id" value="<?= $detailId ?>">
                <input type="hidden" name="id" value="<?= $f['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($facts)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">No facts yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php endif; ?>
</div>

<div class="modal fade" id="sectionModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="/cms/history/action" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="_action" value="save_section">
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
      <input type="hidden" name="id" id="s_id" value="0">
      <input type="hidden" name="detail_id" value="<?= $detailId ?>">
      <div class="modal-header">
        <h5 class="modal-title" id="s_modalTitle">Add Section</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Type</label>
            <select name="section_type" id="s_type" class="form-select">
              <?php foreach (['about','history','highlight','special'] as $t): ?>
                <option value="<?= $t ?>"><?= ucfirst($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="section_title" id="s_title" class="form-control">
          </div>
          <div class="col-md-2">
            <label class="form-label fw-semibold">Order</label>
            <input type="number" name="sort_order" id="s_order" class="form-control" value="0" min="0">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Content</label>
            <textarea name="content" id="s_content" class="form-control" rows="6"></textarea>
            <div class="form-text">Blank lines = new paragraph.</div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Optional Image</label>
            <div id="s_img_preview"></div>
            <input type="file" name="image_path" class="form-control" accept="image/*">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-dark">Save Section</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="factModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="/cms/history/action" class="modal-content">
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
      <input type="hidden" name="_action" value="save_fact">
      <input type="hidden" name="id" id="f_id" value="0">
      <input type="hidden" name="detail_id" value="<?= $detailId ?>">
      <div class="modal-header">
        <h5 class="modal-title" id="f_modalTitle">Add Quick Fact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Icon / Short Value</label>
            <input type="text" name="icon" id="f_icon" class="form-control" placeholder="e.g. 1784 or 75m">
            <div class="form-text">Displayed large on the badge.</div>
          </div>
          <div class="col-md-8">
            <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
            <input type="text" name="label" id="f_label" class="form-control" placeholder="e.g. YEAR FOUNDED" required>
          </div>
          <div class="col-md-8">
            <label class="form-label fw-semibold">Value</label>
            <input type="text" name="value" id="f_value" class="form-control" placeholder="e.g. Oldest in Holland">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Order</label>
            <input type="number" name="sort_order" id="f_order" class="form-control" value="0" min="0">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-dark">Save Fact</button>
      </div>
    </form>
  </div>
</div>

<script>
function openSectionModal(data = null) {
  document.getElementById('s_id').value      = data?.id ?? 0;
  document.getElementById('s_title').value   = data?.section_title ?? '';
  document.getElementById('s_content').value = data?.content ?? '';
  document.getElementById('s_order').value   = data?.sort_order ?? 0;
  document.getElementById('s_modalTitle').textContent = data ? 'Edit Section' : 'Add Section';
  const typeEl = document.getElementById('s_type');
  if (data?.section_type) typeEl.value = data.section_type;
  const prev = document.getElementById('s_img_preview');
  prev.innerHTML = data?.image_path
    ? `<img src="/assets/uploads/History/${data.image_path}" class="img-thumbnail mb-2" style="height:52px;object-fit:cover">`
    : '';
  new bootstrap.Modal(document.getElementById('sectionModal')).show();
}

function openFactModal(data = null) {
  document.getElementById('f_id').value    = data?.id ?? 0;
  document.getElementById('f_icon').value  = data?.icon ?? '';
  document.getElementById('f_label').value = data?.label ?? '';
  document.getElementById('f_value').value = data?.value ?? '';
  document.getElementById('f_order').value = data?.sort_order ?? 0;
  document.getElementById('f_modalTitle').textContent = data ? 'Edit Fact' : 'Add Quick Fact';
  new bootstrap.Modal(document.getElementById('factModal')).show();
}
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
