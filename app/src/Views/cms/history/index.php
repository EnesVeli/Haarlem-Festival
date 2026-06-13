<?php
/** @var \App\ViewModels\Cms\History\HistoryCmsIndexViewModel $viewModel */

$pageTitle = "History CMS";
require __DIR__ . '/../../partials/header.php';

$uploadPath = '/assets/uploads/history/';

// Helper: preview the currently-stored image and keep its filename in a hidden field
// so it's preserved if no new file is uploaded.
function imgPreview(string $uploadPath, ?string $filename, string $field): string
{
    if (!$filename) {
        return '';
    }

    return '<img src="' . $uploadPath . htmlspecialchars($filename) . '" class="thumb mb-1" alt="">'
        . '<input type="hidden" name="' . htmlspecialchars($field) . '_current" value="' . htmlspecialchars($filename) . '">';
}

$sectionLabels = [
    'hero'  => 'Hero',
    'intro' => 'Intro — Golden City',
    'walk'  => 'Better Your Walk',
    'cta'   => 'Call To Action',
];
?>

<div class="container-fluid py-4 history-cms">

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= htmlspecialchars($_SESSION['flash']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="history-cms-header">
    <h1 class="h3 mb-0">History CMS</h1>
    <a href="/history" target="_blank" class="btn btn-sm btn-outline-secondary">Preview page</a>
  </div>

  <ul class="nav nav-tabs mb-4" id="cmsTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-highlights">Highlights</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-tickets">Tickets</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-content">Page Content</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-details">Detail Pages</a></li>
  </ul>

  <div class="tab-content">

    <!-- TAB 1 – HIGHLIGHTS -->
    <div class="tab-pane fade show active" id="tab-highlights">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Route Highlights</strong>
          <button class="btn btn-sm btn-light" onclick="openHighlightModal()">+ Add highlight</button>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr><th>Image</th><th>Title</th><th>Description</th><th>Detail page</th><th style="width:110px"></th></tr>
            </thead>
            <tbody>
              <?php foreach ($viewModel->highlights as $h): ?>
              <tr>
                <td>
                  <?php if ($h->image): ?>
                    <img src="<?= $uploadPath . htmlspecialchars($h->image) ?>" class="thumb" alt="">
                  <?php else: ?><span class="text-muted">&ndash;</span><?php endif; ?>
                </td>
                <td><?= htmlspecialchars($h->title) ?></td>
                <td class="text-muted small"><?= htmlspecialchars(mb_substr($h->description, 0, 70)) ?>&hellip;</td>
                <td>
                  <?php
                    $linkedDetail = null;
                    foreach ($viewModel->details as $d) {
                        if ($d->highlightId === $h->id) {
                            $linkedDetail = $d;
                            break;
                        }
                    }
                  ?>
                  <?php if ($linkedDetail !== null): ?>
                    <a href="/cms/history/detail/<?= $linkedDetail->id ?>" class="badge bg-success text-decoration-none">Edit detail</a>
                  <?php else: ?>
                    <a href="/cms/history/detail/0?highlight_id=<?= $h->id ?>" class="badge bg-secondary text-decoration-none">+ Create</a>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1"
                    onclick='openHighlightModal(<?= htmlspecialchars(json_encode([
                        "id" => $h->id,
                        "title" => $h->title,
                        "description" => $h->description,
                        "image" => $h->image,
                    ]), ENT_QUOTES, "UTF-8") ?>)'>Edit</button>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete this highlight?')">
                    <input type="hidden" name="_action" value="delete_highlight">
                    <input type="hidden" name="id" value="<?= $h->id ?>">
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($viewModel->highlights)): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">No highlights yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB 2 – TICKETS -->
    <div class="tab-pane fade" id="tab-tickets">
      <div class="row g-4">

        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-header">
              <strong>Individual ticket price</strong>
              <small class="d-block text-white-50">Per person &middot; ages 12 and above</small>
            </div>
            <div class="card-body">
              <p class="mb-3">Current price: <span class="price-display">&euro;<?= number_format($viewModel->individualPrice, 2) ?></span></p>
              <form method="POST" action="/cms/history/action">
                <input type="hidden" name="_action" value="save_ticket_price">
                <input type="hidden" name="type" value="0">
                <div class="input-group">
                  <span class="input-group-text">&euro;</span>
                  <input type="number" name="price" class="form-control"
                         step="0.01" min="0"
                         value="<?= number_format($viewModel->individualPrice, 2) ?>"
                         required>
                  <button class="btn btn-primary-accent" type="submit">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-header">
              <strong>Family ticket price</strong>
              <small class="d-block text-white-50">Up to 4 people</small>
            </div>
            <div class="card-body">
              <p class="mb-3">Current price: <span class="price-display">&euro;<?= number_format($viewModel->familyPrice, 2) ?></span></p>
              <form method="POST" action="/cms/history/action">
                <input type="hidden" name="_action" value="save_ticket_price">
                <input type="hidden" name="type" value="1">
                <div class="input-group">
                  <span class="input-group-text">&euro;</span>
                  <input type="number" name="price" class="form-control"
                         step="0.01" min="0"
                         value="<?= number_format($viewModel->familyPrice, 2) ?>"
                         required>
                  <button class="btn btn-primary-accent" type="submit">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- TAB 3 – PAGE CONTENT -->
    <div class="tab-pane fade" id="tab-content">
      <div class="card shadow-sm">
        <div class="card-header"><strong>Page content — Hero / Intro / Walk / CTA</strong></div>
        <div class="card-body">
          <form method="POST" action="/cms/history/action" enctype="multipart/form-data">
            <input type="hidden" name="_action" value="save_content">

            <?php foreach ($sectionLabels as $s => $label): ?>
            <div class="section-block">
              <h6><?= htmlspecialchars($label) ?></h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Title</label>
                  <input type="text" name="<?= $s ?>_title" class="form-control form-control-sm"
                         value="<?= htmlspecialchars($viewModel->contentValue($s, 'title')) ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Subtitle</label>
                  <input type="text" name="<?= $s ?>_subtitle" class="form-control form-control-sm"
                         value="<?= htmlspecialchars($viewModel->contentValue($s, 'subtitle')) ?>">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-semibold">Image</label>
                  <?= imgPreview($uploadPath, $viewModel->contentValue($s, 'image') ?: null, $s . '_img') ?>
                  <input type="file" name="<?= $s ?>_image" class="form-control form-control-sm" accept="image/*">
                </div>
                <?php if ($s === 'intro'): ?>
                <div class="col-md-4">
                  <label class="form-label small fw-semibold">Left image</label>
                  <?= imgPreview($uploadPath, $viewModel->contentValue($s, 'image_left') ?: null, $s . '_img_left') ?>
                  <input type="file" name="<?= $s ?>_image_left" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-semibold">Right image</label>
                  <?= imgPreview($uploadPath, $viewModel->contentValue($s, 'image_right') ?: null, $s . '_img_right') ?>
                  <input type="file" name="<?= $s ?>_image_right" class="form-control form-control-sm" accept="image/*">
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary-accent">Save all content</button>
          </form>
        </div>
      </div>
    </div>

    <!-- TAB 4 – DETAIL PAGES -->
    <div class="tab-pane fade" id="tab-details">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Detail pages</strong>
          <a href="/cms/history/detail/0" class="btn btn-sm btn-light">+ New detail page</a>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr><th>Highlight</th><th>Page title</th><th>Slug</th><th style="width:110px"></th></tr>
            </thead>
            <tbody>
              <?php foreach ($viewModel->details as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d->highlightTitle ?? '–') ?></td>
                <td><?= htmlspecialchars($d->pageTitle) ?></td>
                <td><code>/history/<?= htmlspecialchars($d->slug) ?></code></td>
                <td>
                  <a href="/cms/history/detail/<?= $d->id ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete this detail page and all its content?')">
                    <input type="hidden" name="_action" value="delete_detail">
                    <input type="hidden" name="id" value="<?= $d->id ?>">
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($viewModel->details)): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">No detail pages yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- MODAL – HIGHLIGHT -->
<div class="modal fade" id="highlightModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="/cms/history/action" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="_action" value="save_highlight">
      <input type="hidden" name="id" id="h_id" value="0">
      <div class="modal-header">
        <h5 class="modal-title" id="h_modalTitle">Add highlight</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" name="title" id="h_title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
          <textarea name="description" id="h_description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Card image</label>
          <div id="h_img_preview"></div>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-accent">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
function openHighlightModal(data = null) {
  document.getElementById('h_id').value          = data?.id ?? 0;
  document.getElementById('h_title').value       = data?.title ?? '';
  document.getElementById('h_description').value = data?.description ?? '';
  document.getElementById('h_modalTitle').textContent = data ? 'Edit highlight' : 'Add highlight';

  const prev = document.getElementById('h_img_preview');
  prev.innerHTML = data?.image
    ? `<img src="<?= $uploadPath ?>${data.image}" class="thumb mb-2" alt="">`
    : '';

  new bootstrap.Modal(document.getElementById('highlightModal')).show();
}
</script>

<link href="/assets/css/history-cms.css" rel="stylesheet">

<?php require __DIR__ . '/../../partials/footer.php'; ?>
