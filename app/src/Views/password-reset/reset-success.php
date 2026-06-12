<?
$pageTitle = 'Password Reset Success - The Festival Haarlem';
$mainClass = 'login-main';
$pageCSS = 'login.css';
?>

<?php require '/app/src/Views/partials/header.php';?>

<div class="login-section">
    <div class="login-card">      
        <h1 class="login-title">Password Reset</h1>
        <p class="login-subtitle">Sucessfull password reset.</p>

        <div class="alert alert-success">Your password has been successesfuly reset.</div>

        <button type="button" onclick="onLoginButtonClick()" class="login-btn">Login</button>
    </div>
</div>

<script>
    function onLoginButtonClick(){
        window.location.href = '/login';
    }
</script>

<?php require '/app/src/Views/partials/footer.php';?>