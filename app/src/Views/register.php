<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Haarlem Festival</title>

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
                        <h1 class="h4 mb-3">Create Account</h1>
                        <p class="text-muted mb-4">Sign up to buy tickets and create your personal program.</p>

                        <div id="message-container"></div>

                        <form id="register-form" novalidate>

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

                            <div class="d-grid gap-2">
                                <button type="submit" id="submit-btn" class="btn btn-primary">
                                    Create Account
                                </button>

                                <a class="btn btn-outline-secondary" href="/login">
                                    Already have an account? Login
                                </a>
                            </div>

                            <div id="loading-spinner" class="text-center mt-3 d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                        </form>

                        <div>Forgot your password? <a href="/password-reset-request">Password reset</a></div>
                    </div>
                </div>

                <p class="text-center text-muted mt-3 small">
                    Haarlem Festival &copy; 2026
                </p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById('register-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-btn');
        const spinner = document.getElementById('loading-spinner');
        const messageContainer = document.getElementById('message-container');

        // Reset UI
        messageContainer.innerHTML = '';
        submitBtn.disabled = true;
        spinner.classList.remove('d-none'); // Show spinner

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                // Success Message (Bootstrap Alert)
                messageContainer.innerHTML = `
            <div class="alert alert-success" role="alert">
              ${sanitizeHTML(result.message)}
            </div>`;
                e.target.reset(); // Clear the form
            } else {
                // Error Message (Bootstrap Alert)
                messageContainer.innerHTML = `
            <div class="alert alert-danger" role="alert">
              ${sanitizeHTML(result.message)}
            </div>`;
            }

        } catch (error) {
            messageContainer.innerHTML = `
          <div class="alert alert-danger" role="alert">
            An unexpected error occurred. Please try again.
          </div>`;
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none'); // Hide spinner
        }
    });

    // Helper to prevent XSS
    function sanitizeHTML(str) {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    }
    </script>
</body>

</html>