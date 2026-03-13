<?php
$pageTitle = $pageTitle ?? 'CMS - Edit Block';
require __DIR__ . '/../partials/header.php';

$blockType = $block['block_type'] ?? 'experience';
?>

<div class="container py-4">
  <h1 class="mb-3"><?= $block['id'] ? 'Edit Block' : 'New Block' ?></h1>

  <form method="post" action="<?= $block['id'] ? '/cms/jazz/block/edit' : '/cms/jazz/block/new' ?>" class="card p-3">
    <?php if ($block['id']): ?>
      <input type="hidden" name="id" value="<?= (int)$block['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">Block Type</label>
      <select class="form-select" name="block_type" <?= $block['id'] ? 'disabled' : '' ?>>
        <?php foreach (['experience','performer','recommendation'] as $t): ?>
          <option value="<?= $t ?>" <?= $blockType === $t ? 'selected' : '' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>
      <?php if ($block['id']): ?>
        <div class="form-text">Block type can’t be changed after create (keeps data consistent).</div>
      <?php endif; ?>
    </div>

    <?php if ($blockType === 'performer'): ?>
      <div class="mb-3">
        <label class="form-label">Performer</label>
        <select class="form-select" name="performer_id" required>
          <option value="0">-- Select performer --</option>
          <?php foreach ($performerOptions as $p): ?>
            <option value="<?= (int)$p['id'] ?>" <?= (int)($block['performer_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php else: ?>
      <input type="hidden" name="performer_id" value="0">
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input class="form-control" name="title" value="<?= htmlspecialchars($block['title'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Subtitle (optional)</label>
      <input class="form-control" name="subtitle" value="<?= htmlspecialchars($block['subtitle'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Body (text)</label>
      <textarea class="form-control" name="body" rows="5"><?= htmlspecialchars($block['body'] ?? '') ?></textarea>
    </div>

    <?php if ($blockType === 'recommendation'): ?>
      <div class="mb-3">
        <label class="form-label">URL (e.g. /history)</label>
        <input class="form-control" name="url" value="<?= htmlspecialchars($block['url'] ?? '') ?>">
      </div>
    <?php else: ?>
      <input type="hidden" name="url" value="<?= htmlspecialchars($block['url'] ?? '') ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">Image path (optional)</label>
      <input class="form-control" name="image_path" value="<?= htmlspecialchars($block['image_path'] ?? '') ?>">
      <div class="form-text">Example: /assets/images/jazz/hero.jpg</div>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Sort order</label>
        <input type="number" class="form-control" name="sort_order" value="<?= (int)($block['sort_order'] ?? 0) ?>">
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label">Active</label>
        <select class="form-select" name="is_active">
          <option value="1" <?= (int)($block['is_active'] ?? 1) === 1 ? 'selected' : '' ?>>Yes</option>
          <option value="0" <?= (int)($block['is_active'] ?? 1) === 0 ? 'selected' : '' ?>>No</option>
        </select>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn btn-outline-secondary" href="/cms/jazz/home">Cancel</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>