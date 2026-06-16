<?php

namespace App\Framework;

class Session
{
    public static string $temp_error_session_name = "temp_error";
    public static string $temp_success_session_name = "temp_success";
    public static string $cart_count_name = "cart_count";

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

    public static function pop(string $key, $default = null)
    {
        $value = $_SESSION[$key] ?? $default;
        unset($_SESSION[$key]);
        return $value;
    }

    public static function setTempError(string $error_message){
        self::set(self::$temp_error_session_name, $error_message);
    }

    public static function popTempError() : ?string{
        return self::pop(self::$temp_error_session_name);
    }

    public static function setTempSuccess(string $success_message){
        self::set(self::$temp_success_session_name, $success_message);
    }

    public static function popTempSuccess() : ?string{
        return self::pop(self::$temp_success_session_name);
    }

    public static function login(\App\Models\User $user): void
    {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['email']   = $user->email;
        $_SESSION['name']    = $user->name;
        $_SESSION['role']    = strtolower($user->role->name);
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

    public static function currentUser(): ?\App\Models\User
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        $user = new \App\Models\User();
        $user->user_id = (int)$_SESSION['user_id'];
        $user->email   = $_SESSION['email'] ?? '';
        $user->name    = $_SESSION['name'] ?? '';

        $raw = $_SESSION['role'] ?? null;
        if (is_numeric($raw)) {
            $user->role = \App\Enums\UserRole::from((int)$raw);
        } else {
            $map = ['customer' => \App\Enums\UserRole::Customer, 'admin' => \App\Enums\UserRole::Admin, 'employee' => \App\Enums\UserRole::Employee];
            $user->role = $map[strtolower((string)$raw)] ?? \App\Enums\UserRole::Customer;
        }

        return $user;
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

        return $role === 'admin' || (int)$role === \App\Enums\UserRole::Admin->value;
    }

    public static function isEmployee(): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        $role = $user['role'] ?? null;

        return $role === 'employee' || (int)$role === \App\Enums\UserRole::Employee->value;
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkCsrfToken(?string $token): bool
    {
        if ($token === null || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function getCartItemsCount() : int{
        return self::get(self::$cart_count_name) ?? 0;
    }

    public static function setCartItemsCount(int $new_count) : void{
        self::set(self::$cart_count_name, $new_count);
    }
}