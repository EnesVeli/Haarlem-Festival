<?php
/** @var ?string $error */
?>

<section class="login-section">
    <div class="login-card">
        <h1 class="login-title">Welcome back</h1>
        <p class="login-subtitle">Sign in to your festival account</p>

        <?php if (!empty($error)): ?>
            <div class="login-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" novalidate>
            <div class="login-field">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="name@example.com"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="login-links">
            <a href="/register">Create account</a>
            <a href="/password-reset-request">Forgot password?</a>
        </div>
    </div>
</section>