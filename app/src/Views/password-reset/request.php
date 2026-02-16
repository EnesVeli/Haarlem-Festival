<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Password Reset</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="/assets/css/main.css">
    </head>
    <body class="bg-light">
      <div class="container">
        <div class="row justify-content-center">
          <div class="card shadow-sm border-0 col-12 col-sm-10 col-md-7 col-lg-5">
              <div class="card-body p-4">
                <?php if(!empty($error)): ?>
                  <div class="alert alert-danger" role="alert">
                      <?= htmlspecialchars($error) ?>
                  </div>
                <?php endif; ?>

                <form method="post" action="/password-reset-request" novalidate>
                      <div class="mb-3">
                        <label for="email" class="form-label">Enter your email</label>
                        <input id="email" name="email" type="email" class="form-control" placeholder="name@example.com" required autocomplete="email">
                      </div>

                      <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Sent Password Reset</button>
                      </div>
                </form>
              </div>
          </div>
        </div>
      </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>