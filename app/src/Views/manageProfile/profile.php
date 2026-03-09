<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 720px;">

  <a class="btn btn-outline-secondary mb-3" href="/">Back to home</a>
  <h1 class="h3 mb-3">Manage account</h1>
  <p class="text-muted">Edit your name, email, and profile picture.</p>

  <?php if (!empty($user['profile_picture_url'])): ?>
  <div class="d-flex align-items-center mb-4 gap-3">
    <img
      src="<?= htmlspecialchars($user['profile_picture_url']) ?>"
      alt="Profile picture"
      style="width:96px;height:96px;border-radius:50%;object-fit:cover;"
    >
    <div>
      <h5 class="mb-1"><?= htmlspecialchars($user['name']) ?></h5>
      <p class="text-muted mb-0"><?= htmlspecialchars($user['email']) ?></p>
    </div>
  </div>
<?php endif; ?>


  <?php if (!empty($success)): ?>
    <div class="alert alert-success">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  

  <form method="post" action="/profile/update" enctype="multipart/form-data"
        class="card p-4 shadow-sm border-0">

    <!-- Name -->
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input
        class="form-control"
        name="name"
        value="<?= htmlspecialchars($user['name']) ?>"
      >
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input
        class="form-control"
        type="email"
        name="email"
        value="<?= htmlspecialchars($user['email']) ?>"
      >
    </div>

    <hr>

    <!-- Password reset -->
    <div class="mb-3">
      <h6 class="mt-3">Password</h6>
      <p class="text-muted mb-2">
        To change your password, use the password reset page.
      </p>
      <a class="btn btn-outline-secondary" href="/password-reset-request">
        Reset password
      </a>
    </div>

    <hr>

    <!-- Profile picture -->
    <div class="mb-3">
      <label class="form-label">Profile picture</label>
      <input
        class="form-control"
        type="file"
        name="profile_picture"
        accept=".jpg,.jpeg,.png,.webp"
      >

      <?php if (!empty($user['profile_picture_url'])): ?>
        <div class="mt-3">
          <img
            src="<?= htmlspecialchars($user['profile_picture_url']) ?>"
            alt="Profile picture"
            style="max-width:120px;border-radius:12px;"
          >
        </div>
      <?php endif; ?>
    </div>

    <button class="btn btn-primary w-100">
      Save changes
    </button>

  </form>

</div>

</body>
</html>
