<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">Haarlem Festival</a>

    <div class="ms-auto d-flex gap-2">
      <a class="btn btn-outline-dark" href="/jazz">Jazz</a>

      <?php if (!empty($_SESSION['user_id'])): ?>
  <a class="btn btn-outline-primary" href="/profile">Manage profile</a>
  <a class="btn btn-outline-danger" href="/logout">Logout</a>
<?php else: ?>
        <a class="btn btn-outline-primary" href="/login">Login</a>
        <a class="btn btn-primary" href="/register">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="card shadow-sm border-0">
    <div class="card-body p-4 p-md-5">

      <?php if (!empty($_SESSION['user_id'])): ?>
        <h1 class="h4 mb-2">Welcome back, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h1>
        <p class="text-muted mb-4">
          Logged in as <?= htmlspecialchars($_SESSION['email']) ?> — role: <?= htmlspecialchars($_SESSION['role']) ?>
        </p>

        <div class="d-flex gap-2">
  <a class="btn btn-dark" href="/jazz">Go to Jazz page</a>
  <a class="btn btn-outline-primary" href="/profile">Manage profile</a>
  <a class="btn btn-outline-danger" href="/logout">Logout</a>
</div>

      <?php else: ?>
        <h1 class="h4 mb-2">Welcome to Haarlem Festival 🎷</h1>
        <p class="text-muted mb-4">
          Browse events as a visitor. Login only when you want to book tickets or manage your profile.
        </p>

        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary" href="/login">Login</a>
          <a class="btn btn-outline-secondary" href="/register">Create account</a>
          <a class="btn btn-outline-dark" href="/jazz">Browse Jazz events</a>
        </div>

        <div class="alert alert-light border mt-4 mb-0">
          Visitor mode: no account needed to browse.
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

</body>
</html>