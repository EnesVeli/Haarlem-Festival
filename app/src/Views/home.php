<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/">Haarlem Festival</a>

    <div class="ms-auto d-flex gap-2">
      <?php if (!empty($_SESSION['user_id'])): ?>
        <span class="navbar-text small text-muted">
          <?= htmlspecialchars($_SESSION['name']) ?>
        </span>

        <form method="post" action="/logout" class="d-inline">
          <button class="btn btn-sm btn-outline-danger" type="submit">Logout</button>
        </form>
      <?php else: ?>
        <a class="btn btn-sm btn-outline-primary" href="/login">Login</a>
        <a class="btn btn-sm btn-primary" href="/register">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8">

      <?php if (!empty($_GET['loggedout'])): ?>
        <div class="alert alert-success">
          You are now logged out.
        </div>
      <?php endif; ?>

      <div class="card shadow-sm border-0">
        <div class="card-body p-4">

          <?php if (!empty($_SESSION['user_id'])): ?>
            <h1 class="h4 mb-2">Welcome back, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h1>
            <p class="text-muted mb-4">
              Logged in as <?= htmlspecialchars($_SESSION['email']) ?> · Role: <?= htmlspecialchars($_SESSION['role']) ?>
            </p>

            <div class="d-flex gap-2 flex-wrap">
              <a class="btn btn-primary" href="/jazz">Go to Jazz events</a>
              <a class="btn btn-outline-secondary" href="/profile">My profile</a>
            </div>

            <hr class="my-4">

            <form method="post" action="/logout">
              <button class="btn btn-danger" type="submit">Logout</button>
            </form>

          <?php else: ?>
            <h1 class="h4 mb-2">Welcome to Haarlem Festival 🎷</h1>
            <p class="text-muted mb-4">
              Browse events as a visitor. Login only when you want to book tickets or manage your profile.
            </p>

            <div class="d-flex gap-2 flex-wrap">
              <a class="btn btn-primary" href="/login">Login</a>
              <a class="btn btn-outline-secondary" href="/register">Create account</a>
              <a class="btn btn-outline-dark" href="/jazz">Browse Jazz events</a>
            </div>

            <div class="mt-4 p-3 bg-light rounded border">
              <div class="small text-muted">
                Visitor mode: no account needed to browse.
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>