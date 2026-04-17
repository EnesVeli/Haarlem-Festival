<?php

namespace App\Framework;

class Session
{
    public static string $temp_error_session_name = "temp_error";
    public static string $temp_success_session_name = "temp_success";

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
        Session::set(Session::$temp_error_session_name, $error_message);
    }

    public static function popTempError() : ?string{
        return Session::pop(Session::$temp_error_session_name);
    }

    public static function setTempSuccess(string $success_message){
        Session::set(Session::$temp_success_session_name, $success_message);
    }

    public static function popTempSuccess() : ?string{
        return Session::pop(Session::$temp_success_session_name);
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