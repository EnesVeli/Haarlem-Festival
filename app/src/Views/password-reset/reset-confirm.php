<?
$pageTitle = 'Password Reset Confirm - The Festival Haarlem';
$mainClass = 'login-main';
$pageCSS = 'login.css';
?>

<?php require '/app/src/Views/partials/header.php';?>

<div class="login-section">
    <div class="login-card">      
        <h1 class="login-title">Confirm Password Reset</h1>
        <p class="login-subtitle">Verify your email to reset your password.</p>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/password-reset-confirm" novalidate>
            <input type="hidden" id="key" name="key" value="<? echo htmlspecialchars($key); ?>">

            <div class="login-field">
                <label for="email">Enter your email</label>
                <input id="email" name="email" type="email" placeholder="name@example.com" required autocomplete="email">
            </div>

            <button type="submit" class="login-btn">Verify</button>

            <div class="login-links">
                <a href="/login">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>