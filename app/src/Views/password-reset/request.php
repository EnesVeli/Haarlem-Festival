<?
$pageTitle = 'Password Reset Request - The Festival Haarlem';
$mainClass = 'login-main';
$pageCSS = 'login.css';
?>

<?php require '/app/src/Views/partials/header.php';?>

<div class="login-section">
    <div class="login-card">      
        <h1 class="login-title">Password Reset Request</h1>
        <p class="login-subtitle">Request an password request.</p>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/password-reset-request" novalidate>
            <div class="login-field">
                <label for="email">Enter your email</label>
                <input id="email" name="email" type="email" placeholder="name@example.com" required autocomplete="email">
            </div>

            <button type="submit" class="login-btn">Sent Password Reset</button>

            <div class="login-links">
                <a href="/login">Know the password? Login</a>
                <a href="/register">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>