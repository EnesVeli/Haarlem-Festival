<?php
/**
 * @var array<int, array<string, mixed>> $highlights
 * @var array<string, array<string, mixed>> $content
 * @var array<int, array<string, mixed>> $details
 * @var int $individual_price
 * @var int $family_price
 */
$pageTitle = "CMS – History";
$pageCSS = 'cms-home-history.css';
require __DIR__ . '/../../partials/header.php';

function imgPreview(string $filename, string $field): string {
    if (!$filename) return '';
    return '<img src="/assets/uploads/History/' . htmlspecialchars($filename) . '" class="img-thumbnail mb-1 cms-preview-img">'
         . '<input type="hidden" name="' . $field . '_current" value="' . htmlspecialchars($filename) . '">';
}
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
      <p class="cms-eyebrow">Content management</p>
      <h1>History CMS</h1>
    </div>
    <a href="/history" target="_blank" class="btn btn-outline-secondary">Preview site</a>
  </div>

  <ul class="nav nav-tabs cms-tabs mb-4" id="cmsTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-highlights">Highlights</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-tickets">Tickets</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-content">Page Content</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-details">Detail Pages</a></li>
  </ul>

  <div class="tab-content">

    <div class="tab-pane fade show active" id="tab-highlights">
      <div class="card cms-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Route Highlights</strong>
          <button class="btn btn-sm btn-light" onclick="openHighlightModal()">Add highlight</button>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr><th>Image</th><th>Title</th><th>Description</th><th>Detail Page</th><th style="width:110px"></th></tr>
            </thead>
            <tbody>
              <?php foreach ($highlights as $h): ?>
              <tr>
                <td>
                  <?php if ($h['image']): ?>
                    <img src="/assets/uploads/History/<?= htmlspecialchars($h['image']) ?>" class="cms-preview-img" alt="">
                  <?php else: ?><span class="text-muted">-</span><?php endif; ?>
                </td>
                <td><?= htmlspecialchars($h['title']) ?></td>
                <td class="text-muted small"><?= htmlspecialchars(mb_substr($h['description'], 0, 70)) ?>...</td>
                <td>
                  <?php
                    $det = array_values(array_filter($details, fn($d) => $d['highlight_id'] == $h['id']));
                  ?>
                  <?php if (!empty($det)): ?>
                    <a href="/cms/history/detail/<?= $det[0]['id'] ?>" class="badge bg-success text-decoration-none">Edit detail</a>
                  <?php else: ?>
                    <a href="/cms/history/detail/0?highlight_id=<?= $h['id'] ?>" class="badge bg-secondary text-decoration-none">Create</a>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1"
                    onclick='openHighlightModal(<?= json_encode($h) ?>)'>Edit</button>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete?')">
                    <input type="hidden" name="_action" value="delete_highlight">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= $h['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($highlights)): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">No highlights yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="tab-pane fade" id="tab-tickets">
      <div class="row g-4">

        <div class="col-md-6">
          <div class="card cms-card h-100">
            <div class="card-header">
              <strong>Individual Ticket Price</strong>
              <small class="text-white-50 d-block">Per person &middot; Ages 12 and above</small>
            </div>
            <div class="card-body">
              <p class="text-muted mb-3">Current price: <strong>€<?= number_format($individual_price / 100, 2) ?></strong></p>
              <form method="POST" action="/cms/history/action">
                <input type="hidden" name="_action" value="save_ticket_price">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
                <input type="hidden" name="type" value="0">
                <div class="input-group">
                  <span class="input-group-text">€</span>
                  <input type="number" name="price" class="form-control"
                         step="0.01" min="0"
                         value="<?= number_format($individual_price / 100, 2) ?>"
                         required>
                  <button class="btn btn-dark" type="submit">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card cms-card h-100">
            <div class="card-header">
              <strong>Family Ticket Price</strong>
              <small class="text-white-50 d-block">Up to 4 people</small>
            </div>
            <div class="card-body">
              <p class="text-muted mb-3">Current price: <strong>€<?= number_format($family_price / 100, 2) ?></strong></p>
              <form method="POST" action="/cms/history/action">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
                <input type="hidden" name="_action" value="save_ticket_price">
                <input type="hidden" name="type" value="1">
                <div class="input-group">
                  <span class="input-group-text">€</span>
                  <input type="number" name="price" class="form-control"
                         step="0.01" min="0"
                         value="<?= number_format($family_price / 100, 2) ?>"
                         required>
                  <button class="btn btn-dark" type="submit">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="tab-pane fade" id="tab-content">
      <div class="card cms-card">
        <div class="card-header"><strong>Page Content</strong></div>
        <div class="card-body">
          <form method="POST" action="/cms/history/action" enctype="multipart/form-data">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
            <input type="hidden" name="_action" value="save_content">

            <?php
              $sectionLabels = ['hero'=>'Hero','intro'=>'Intro','walk'=>'Walking section','cta'=>'Call to action'];
            ?>
            <?php foreach ($sectionLabels as $s => $label):
              $c = $content[$s] ?? [];
            ?>
            <div class="cms-section-panel mb-3">
              <h6 class="fw-bold mb-3"><?= $label ?></h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Title</label>
                  <input type="text" name="<?= $s ?>_title" class="form-control form-control-sm"
                         value="<?= htmlspecialchars($c['title'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Subtitle</label>
                  <input type="text" name="<?= $s ?>_subtitle" class="form-control form-control-sm"
                         value="<?= htmlspecialchars($c['subtitle'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-semibold">Image</label>
                  <?= imgPreview($c['image'] ?? '', $s . '_img') ?>
                  <input type="file" name="<?= $s ?>_image" class="form-control form-control-sm" accept="image/*">
                </div>
                <?php if ($s === 'intro'): ?>
                <div class="col-md-4">
                  <label class="form-label small fw-semibold">Left Image</label>
                  <?= imgPreview($c['image_left'] ?? '', $s . '_img_left') ?>
                  <input type="file" name="<?= $s ?>_image_left" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-semibold">Right Image</label>
                  <?= imgPreview($c['image_right'] ?? '', $s . '_img_right') ?>
                  <input type="file" name="<?= $s ?>_image_right" class="form-control form-control-sm" accept="image/*">
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-dark">Save all content</button>
          </form>
        </div>
      </div>
    </div>

    <div class="tab-pane fade" id="tab-details">
      <div class="card cms-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Detail Pages</strong>
          <a href="/cms/history/detail/0" class="btn btn-sm btn-light">New detail page</a>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr><th>Highlight</th><th>Page Title</th><th>Slug</th><th style="width:110px"></th></tr>
            </thead>
            <tbody>
              <?php foreach ($details as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d['highlight_title'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['page_title']) ?></td>
                <td><code>/history/<?= htmlspecialchars($d['slug']) ?></code></td>
                <td>
                  <a href="/cms/history/detail/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete this detail page and all its content?')">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
                    <input type="hidden" name="_action" value="delete_detail">
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($details)): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">No detail pages yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="highlightModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="/cms/history/action" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
      <input type="hidden" name="_action" value="save_highlight">
      <input type="hidden" name="id" id="h_id" value="0">
      <div class="modal-header">
        <h5 class="modal-title" id="h_modalTitle">Add Highlight</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
          <label class="form-label fw-semibold">Card Image</label>
          <div id="h_img_preview"></div>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-dark">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
function openHighlightModal(data = null) {
  document.getElementById('h_id').value          = data?.id ?? 0;
  document.getElementById('h_title').value       = data?.title ?? '';
  document.getElementById('h_description').value = data?.description ?? '';
  document.getElementById('h_modalTitle').textContent = data ? 'Edit Highlight' : 'Add Highlight';
  const prev = document.getElementById('h_img_preview');
  prev.innerHTML = data?.image
    ? '<img src="/assets/uploads/History/' + data.image + '" class="img-thumbnail mb-2" style="height:56px;object-fit:cover">'
    : '';
  new bootstrap.Modal(document.getElementById('highlightModal')).show();
}
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>