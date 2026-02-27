<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Password Reset</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            <?php include '/app/public/assets/css/main.css'; ?>
        </style>
    </head>
    <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card shadow-sm border-0 mt-5 col-12 col-sm-10 col-md-7 col-lg-5">
                    <div class="card-body p-4">
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/password-reset" novalidate>
                            <div class="mb-3">
                                <label for="password" class="form-label">Enter new password</label>
                                <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Confirm new password</label>
                                <input id="password-confirm" name="password-confirm" type="password" class="form-control" required autocomplete="new-password">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                                <a class="btn btn-outline-secondary" href="/login">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>