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

    public static function getCartItemsCount() : int{
        return self::get(self::$cart_count_name) ?? 0;
    }

    public static function setCartItemsCount(int $new_count) : void{
        self::set(self::$cart_count_name, $new_count);
    }
}