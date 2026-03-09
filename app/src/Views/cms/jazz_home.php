<?php
$pageTitle = $pageTitle ?? 'CMS - Jazz Homepage';
require __DIR__ . '/../partials/header.php';
?>

<div class="container py-4">

  <h1 class="mb-3">Jazz Homepage CMS</h1>
  <p class="text-muted mb-4">Manage blocks shown on the Jazz homepage (Experiences, Performers, Recommendations).</p>

  <!-- EXPERIENCES -->
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="mb-0">Experiences</h3>
    <a class="btn btn-sm btn-primary" href="/cms/jazz/block/new?type=experience">+ New</a>
  </div>

  <div class="table-responsive mb-5">
    <table class="table table-striped align-middle">
      <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Sort</th>
        <th>Active</th>
        <th style="width:160px;">Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($experiences as $b): ?>
        <tr>
          <td><?= (int)$b['id'] ?></td>
          <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
          <td><?= (int)($b['sort_order'] ?? 0) ?></td>
          <td><?= (int)($b['is_active'] ?? 0) === 1 ? 'Yes' : 'No' ?></td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="/cms/jazz/block/edit?id=<?= (int)$b['id'] ?>">Edit</a>
            <form method="post" action="/cms/jazz/block/delete" class="d-inline" onsubmit="return confirm('Delete this block?')">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- PERFORMERS -->
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="mb-0">Performers</h3>
    <a class="btn btn-sm btn-primary" href="/cms/jazz/block/new?type=performer">+ New</a>
  </div>

  <div class="table-responsive mb-5">
    <table class="table table-striped align-middle">
      <thead>
      <tr>
        <th>ID</th>
        <th>Performer</th>
        <th>CMS Title</th>
        <th>Sort</th>
        <th>Active</th>
        <th style="width:160px;">Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($performers as $b): ?>
        <tr>
          <td><?= (int)$b['id'] ?></td>
          <td><?= htmlspecialchars($b['performer_name'] ?? 'Unknown') ?></td>
          <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
          <td><?= (int)($b['sort_order'] ?? 0) ?></td>
          <td><?= (int)($b['is_active'] ?? 0) === 1 ? 'Yes' : 'No' ?></td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="/cms/jazz/block/edit?id=<?= (int)$b['id'] ?>">Edit</a>
            <form method="post" action="/cms/jazz/block/delete" class="d-inline" onsubmit="return confirm('Delete this block?')">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- RECOMMENDATIONS -->
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="mb-0">Recommendations</h3>
    <a class="btn btn-sm btn-primary" href="/cms/jazz/block/new?type=recommendation">+ New</a>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>URL</th>
        <th>Sort</th>
        <th>Active</th>
        <th style="width:160px;">Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($recs as $b): ?>
        <tr>
          <td><?= (int)$b['id'] ?></td>
          <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
          <td><?= htmlspecialchars($b['url'] ?? '') ?></td>
          <td><?= (int)($b['sort_order'] ?? 0) ?></td>
          <td><?= (int)($b['is_active'] ?? 0) === 1 ? 'Yes' : 'No' ?></td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="/cms/jazz/block/edit?id=<?= (int)$b['id'] ?>">Edit</a>
            <form method="post" action="/cms/jazz/block/delete" class="d-inline" onsubmit="return confirm('Delete this block?')">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>