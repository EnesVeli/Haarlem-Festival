<?php

use App\Models\User;

/**
 * @var User  $user
 * @var ?string $success
 * @var ?string $error
 */
$pageTitle = 'Manage Profile — The Festival Haarlem';
$mainClass = 'profile-main';
$sessionUser = \App\Framework\Session::currentUser();

require __DIR__ . '/../partials/header.php';
?>

<section class="profile-section">
    <div class="profile-card">

        <a href="/" class="profile-back">&larr; Back to home</a>

        <?php if (!empty($user->profile_picture_url)): ?>
        <div class="profile-avatar-row">
            <img
                src="<?= htmlspecialchars($user->profile_picture_url) ?>"
                alt="Profile picture"
                class="profile-avatar"
            >
            <div>
                <p class="profile-avatar-name"><?= htmlspecialchars($user->name) ?></p>
                <p class="profile-avatar-email"><?= htmlspecialchars($user->email) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <h1 class="profile-title">Manage account</h1>
        <p class="profile-subtitle">Edit your name, email, and profile picture.</p>

        <?php if (!empty($success)): ?>
            <div class="profile-alert profile-alert--success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="profile-alert profile-alert--error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/profile/update" enctype="multipart/form-data">

            <div class="profile-field">
                <label for="name">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="<?= htmlspecialchars($user->name) ?>"
                >
            </div>

            <div class="profile-field">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="<?= htmlspecialchars($user->email) ?>"
                >
            </div>

            <hr class="profile-divider">

            <div class="profile-field">
                <p class="profile-field-label">Password</p>
                <p class="profile-field-hint">To change your password, use the password reset page.</p>
                <a href="/password-reset-request" class="profile-link-btn">Reset password</a>
            </div>

            <hr class="profile-divider">

            <div class="profile-field">
                <label for="profile_picture">Profile picture</label>
                <input
                    id="profile_picture"
                    name="profile_picture"
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="profile-file-input"
                >
                <?php if (!empty($user->profile_picture_url)): ?>
                    <div class="profile-picture-preview">
                        <img
                            src="<?= htmlspecialchars($user->profile_picture_url) ?>"
                            alt="Current profile picture"
                        >
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="profile-btn">Save changes</button>

        </form>
    </div>
</section>

<style>
.profile-main {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--neutral-light);
    padding: 2rem 1rem;
}

.profile-section {
    width: 100%;
    display: flex;
    justify-content: center;
}

.profile-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    padding: 36px 36px;
    width: 100%;
    max-width: 520px;
}

.profile-back {
    display: inline-block;
    color: var(--navy);
    font-size: 14px;
    text-decoration: none;
    margin-bottom: 20px;
}

.profile-back:hover {
    color: var(--burgundy);
    text-decoration: underline;
}

.profile-avatar-row {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border-color);
}

.profile-avatar-name {
    font-weight: 600;
    font-size: 15px;
    color: var(--dark-text);
    margin: 0 0 2px;
}

.profile-avatar-email {
    font-size: 13px;
    color: var(--light-text);
    margin: 0;
}

.profile-title {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: var(--burgundy);
    margin-bottom: 6px;
}

.profile-subtitle {
    color: var(--light-text);
    font-size: 14px;
    margin-bottom: 24px;
}

.profile-alert {
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    margin-bottom: 20px;
}

.profile-alert--success {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #166534;
}

.profile-alert--error {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #b91c1c;
}

.profile-field {
    margin-bottom: 18px;
}

.profile-field label,
.profile-field-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 6px;
}

.profile-field-hint {
    font-size: 13px;
    color: var(--light-text);
    margin-bottom: 10px;
}

.profile-field input[type="text"],
.profile-field input[type="email"] {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    color: var(--dark-text);
    outline: none;
    transition: border-color var(--transition-base);
    box-sizing: border-box;
}

.profile-field input:focus {
    border-color: var(--navy);
}

.profile-file-input {
    display: block;
    width: 100%;
    font-size: 14px;
    color: var(--medium-text);
    padding: 8px 0;
}

.profile-picture-preview {
    margin-top: 12px;
}

.profile-picture-preview img {
    max-width: 100px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.profile-divider {
    border: none;
    border-top: 1px solid var(--border-color);
    margin: 20px 0;
}

.profile-link-btn {
    display: inline-block;
    padding: 9px 18px;
    border: 1.5px solid var(--navy);
    border-radius: 8px;
    color: var(--navy);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background var(--transition-base), color var(--transition-base);
}

.profile-link-btn:hover {
    background: var(--navy);
    color: #fff;
}

.profile-btn {
    width: 100%;
    padding: 13px;
    background: var(--navy);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Playfair Display', serif;
    cursor: pointer;
    margin-top: 8px;
    transition: background var(--transition-base), transform var(--transition-base);
}

.profile-btn:hover {
    background: var(--navy-dark);
    transform: translateY(-1px);
}
</style>

<?php require __DIR__ . '/../partials/footer.php'; ?>
