<?php
/**
 * Renders the user registration form, with an optional validation error message.
 *
 * @var string      $csrfToken     CSRF token embedded in the registration form's hidden field.
 * @var string|null $error_message Validation error shown above the form, or null if there is none.
 */
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Haarlem Festival</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
    <?php include '/app/public/assets/css/main.css';
    ?>
    </style>
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5">

                <div class="card shadow-sm border-0 mt-5">
                    <div class="card-body p-4">
                        <?php if(!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error_message) ?>
                        </div>
                        <?php endif; ?>

                        <h1 class="h4 mb-3">Create Account</h1>
                        <p class="text-muted mb-4">Sign up to buy tickets and create your personal program.</p>

                        <div id="message-container"></div>

                        <form method="post" action="/register" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="e.g. John Doe" required autocomplete="name">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="name@example.com" required autocomplete="email">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Min. 8 characters" minlength="8" required autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Password</label>
                                <input type="password" id="password-confirm" name="password-confirm"
                                    class="form-control" placeholder="Min. 8 characters" minlength="8" required
                                    autocomplete="new-password">
                            </div>

                            <div class="mb-3 d-flex justify-content-center">
                                <div class="g-recaptcha" data-sitekey="<?= \App\Config::RECAPTCHA_SITE_KEY ?>"></div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" id="submit-btn" class="btn btn-primary">
                                    Create Account
                                </button>
                                <a class="btn btn-outline-secondary" href="/login">
                                    Already have an account? Login
                                </a>

                                <a class="btn btn-outline-secondary" href="/password-reset-request">
                                    Forgot your password? Password reset
                                </a>
                            </div>

                            <div id="loading-spinner" class="text-center mt-3 d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <p class="text-center text-muted mt-3 small">
                    Haarlem Festival &copy; 2026
                </p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>