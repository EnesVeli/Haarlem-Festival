<?php

namespace App\Framework;

class Session
{
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, $default = null)
    {
        $value = $_SESSION[$key] ?? $default;
        unset($_SESSION[$key]);
        return $value;
    }

    public static function login(array $user): void
    {
        $_SESSION['user_id'] = $user['user_id'] ?? $user['id'] ?? null;
        $_SESSION['email']   = $user['email'] ?? null;
        $_SESSION['name']    = $user['name'] ?? null;
        $_SESSION['role']    = $user['role'] ?? null;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'user_id' => $_SESSION['user_id'],
            'email'   => $_SESSION['email'] ?? null,
            'name'    => $_SESSION['name'] ?? null,
            'role'    => $_SESSION['role'] ?? null,
        ];
    }
    public static function role(): ?string
{
    return $_SESSION['role'] ?? null;
}

public static function isAdmin(): bool
{
    $user = self::user();
    if (!$user) {
        return false;
    }

    $role = $user['role'] ?? null;

    // supports both formats: 'admin' OR 1
    return $role === 'admin' || (int)$role === 1;
}
}