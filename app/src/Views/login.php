<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    <?php include '/app/public/assets/css/main.css'; ?>
  </style>
</head>
<body class="bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-7 col-lg-5">

        <div class="card shadow-sm border-0 mt-5">
          <div class="card-body p-4">
            <h1 class="h4 mb-3">Login</h1>
            <p class="text-muted mb-4">Sign in with your email and password.</p>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>

            <form method="post" action="/login" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  id="email"
                  name="email"
                  type="email"
                  class="form-control"
                  placeholder="name@example.com"
                  required
                  autocomplete="email"
                >
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  id="password"
                  name="password"
                  type="password"
                  class="form-control"
                  placeholder="••••••••"
                  required
                  autocomplete="current-password"
                >
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                  Login
                </button>
                <a class="btn btn-outline-secondary" href="/register">
                  Create account
                </a>
              </div>

            </form>

            <div>Forgot your password? <a href="/password-reset-request">Password reset</a></div>
          </div>
        </div>

        <p class="text-center text-muted mt-3 small">
          Haarlem Festival
        </p>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>