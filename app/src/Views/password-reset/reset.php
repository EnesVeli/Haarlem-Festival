<?
$pageTitle = 'Password Reset - The Festival Haarlem';
$mainClass = 'login-main';
$pageCSS = 'login.css';
?>

<?php require '/app/src/Views/partials/header.php';?>

<div class="login-section">
    <div class="login-card">      
        <h1 class="login-title">Password Reset</h1>
        <p class="login-subtitle">Make a new password and confirm it.</p>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/password-reset" novalidate>
            <input type="hidden" id="key" name="key" value="<? echo $key?>">
            <input type="hidden" id="email" name="email" value="<? echo $email?>">

            <div class="login-field">
                <label for="password">Enter new password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password">
            </div>

            <div class="login-field">
                <label for="password_confirm">Confirm new password</label>
                <input id="password_confirm" name="password_confirm" type="password" required autocomplete="new-password">
            </div>

            <button type="submit" class="login-btn">Reset Password</button>

            <div class="login-links">
                <a href="/login">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require '/app/src/Views/partials/footer.php';?>