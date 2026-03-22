<?php
$pageTitle = "CMS – History";
require __DIR__ . '/../../partials/header.php';

// helper: current image preview
function imgPreview(string $filename, string $field): string {
    if (!$filename) return '';
    return '<img src="/assets/uploads/History/' . htmlspecialchars($filename) . '" class="img-thumbnail mb-1" style="height:56px;object-fit:cover">
            <input type="hidden" name="' . $field . '_current" value="' . htmlspecialchars($filename) . '">';
}
?>

<div class="container-fluid py-4">

  <!-- Flash -->
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= htmlspecialchars($_SESSION['flash']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📜 History CMS</h1>
    <a href="/history" target="_blank" class="btn btn-sm btn-outline-secondary">Preview ↗</a>
  </div>

  <!-- ══════════════════════════════════════════════════════════════════════
       TAB NAVIGATION
  ══════════════════════════════════════════════════════════════════════════ -->
  <ul class="nav nav-tabs mb-4" id="cmsTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-highlights">🏛 Highlights</a></li>
    <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#tab-tickets">🎟 Tickets</a></li>
    <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#tab-content">📝 Page Content</a></li>
    <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#tab-details">📄 Detail Pages</a></li>
  </ul>

  <div class="tab-content">

    <!-- ════════════════════════════════════════════════════
         TAB 1 – HIGHLIGHTS
    ════════════════════════════════════════════════════ -->
    <div class="tab-pane fade show active" id="tab-highlights">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
          <strong>Route Highlights</strong>
          <button class="btn btn-sm btn-light" onclick="openHighlightModal()">+ Add</button>
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
                    <img src="/assets/uploads/History/<?= htmlspecialchars($h['image']) ?>" style="width:56px;height:40px;object-fit:cover;border-radius:4px">
                  <?php else: ?><span class="text-muted">–</span><?php endif; ?>
                </td>
                <td><?= htmlspecialchars($h['title']) ?></td>
                <td class="text-muted small"><?= htmlspecialchars(mb_substr($h['description'], 0, 70)) ?>…</td>
                <td>
                  <?php
                    $det = array_values(array_filter($details, fn($d) => $d['highlight_id'] == $h['id']));
                  ?>
                  <?php if (!empty($det)): ?>
                    <a href="/cms/history/detail/<?= $det[0]['id'] ?>" class="badge bg-success text-decoration-none">Edit detail</a>
                  <?php else: ?>
                    <a href="/cms/history/detail/0?highlight_id=<?= $h['id'] ?>" class="badge bg-secondary text-decoration-none">+ Create</a>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1"
                    onclick='openHighlightModal(<?= json_encode($h) ?>)'>Edit</button>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete?')">
                    <input type="hidden" name="_action" value="delete_highlight">
                    <input type="hidden" name="id" value="<?= $h['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Del</button>
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

    <!-- ════════════════════════════════════════════════════
         TAB 2 – TICKETS
    ════════════════════════════════════════════════════ -->
    <div class="tab-pane fade" id="tab-tickets">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
          <strong>Tour Ticket Slots</strong>
          <button class="btn btn-sm btn-light" onclick="openTicketModal()">+ Add</button>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr><th>Time Slot</th><th>Price (€)</th><th>Spots</th><th style="width:110px"></th></tr>
            </thead>
            <tbody>
              <?php foreach ($tickets as $t): ?>
              <tr>
                <td><?= htmlspecialchars($t['time_slot']) ?></td>
                <td>€<?= number_format($t['price'], 2) ?></td>
                <td><?= (int)$t['available_spots'] ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1"
                    onclick='openTicketModal(<?= json_encode($t) ?>)'>Edit</button>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete?')">
                    <input type="hidden" name="_action" value="delete_ticket">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Del</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($tickets)): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">No slots yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ════════════════════════════════════════════════════
         TAB 3 – PAGE CONTENT
    ════════════════════════════════════════════════════ -->
    <div class="tab-pane fade" id="tab-content">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white"><strong>Page Content (Hero / Intro / Walk / CTA)</strong></div>
        <div class="card-body">
          <form method="POST" action="/cms/history/action" enctype="multipart/form-data">
            <input type="hidden" name="_action" value="save_content">

            <?php
              $sectionLabels = ['hero'=>'🌅 Hero','intro'=>'🏙 Intro (Golden City)','walk'=>'🚶 Better Your Walk','cta'=>'📢 CTA'];
            ?>
            <?php foreach ($sectionLabels as $s => $label):
              $c = $content[$s] ?? [];
            ?>
            <div class="border rounded p-3 mb-3">
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

            <button type="submit" class="btn btn-dark">💾 Save All Content</button>
          </form>
        </div>
      </div>
    </div>

    <!-- ════════════════════════════════════════════════════
         TAB 4 – DETAIL PAGES
    ════════════════════════════════════════════════════ -->
    <div class="tab-pane fade" id="tab-details">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
          <strong>Detail Pages</strong>
          <a href="/cms/history/detail/0" class="btn btn-sm btn-light">+ New Detail Page</a>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr><th>Highlight</th><th>Page Title</th><th>Slug</th><th style="width:110px"></th></tr>
            </thead>
            <tbody>
              <?php foreach ($details as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d['highlight_title'] ?? '–') ?></td>
                <td><?= htmlspecialchars($d['page_title']) ?></td>
                <td><code>/history/<?= htmlspecialchars($d['slug']) ?></code></td>
                <td>
                  <a href="/cms/history/detail/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                  <form method="POST" action="/cms/history/action" class="d-inline"
                        onsubmit="return confirm('Delete this detail page and all its content?')">
                    <input type="hidden" name="_action" value="delete_detail">
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Del</button>
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

  </div><!-- /tab-content -->
</div>

<!-- ══════════════════════════════════════════════════════════════════════
     MODAL – HIGHLIGHT
══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="highlightModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="/cms/history/action" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="_action" value="save_highlight">
      <input type="hidden" name="id" id="h_id" value="0">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="h_modalTitle">Add Highlight</h5>
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

<!-- ══════════════════════════════════════════════════════════════════════
     MODAL – TICKET
══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="ticketModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <form method="POST" action="/cms/history/action" class="modal-content">
      <input type="hidden" name="_action" value="save_ticket">
      <input type="hidden" name="id" id="t_id" value="0">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="t_modalTitle">Add Ticket Slot</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Time Slot</label>
          <input type="text" name="time_slot" id="t_time_slot" class="form-control" placeholder="e.g. 10:00 AM" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Price (€)</label>
          <input type="number" name="price" id="t_price" class="form-control" step="0.01" min="0" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Available Spots</label>
          <input type="number" name="available_spots" id="t_spots" class="form-control" min="0" required>
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
    ? `<img src="/assets/uploads/History/${data.image}" class="img-thumbnail mb-2" style="height:56px;object-fit:cover">`
    : '';
  new bootstrap.Modal(document.getElementById('highlightModal')).show();
}

function openTicketModal(data = null) {
  document.getElementById('t_id').value        = data?.id ?? 0;
  document.getElementById('t_time_slot').value = data?.time_slot ?? '';
  document.getElementById('t_price').value     = data?.price ?? '';
  document.getElementById('t_spots').value     = data?.available_spots ?? '';
  document.getElementById('t_modalTitle').textContent = data ? 'Edit Ticket Slot' : 'Add Ticket Slot';
  new bootstrap.Modal(document.getElementById('ticketModal')).show();
}
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>