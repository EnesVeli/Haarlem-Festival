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
                        <?php if(!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error_message) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/password-reset-confirm" novalidate>
                            <input type="hidden" id="key" name="key" value="<? echo htmlspecialchars($key); ?>">

                            <div class="mb-3">
                                <label for="email" class="form-label">Verify your email</label>
                                <input id="email" name="email" type="email" class="form-control" placeholder="name@example.com" required autocomplete="email">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Verify</button>
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