<?php
namespace App\Framework;

class Auth
{
    public static function requireAdmin(): void
    {
        $user = Session::user();
        $role = $user['role'] ?? null;

        if (!$user || $role !== 'admin') {
            http_response_code(403);
            echo '403 - Admin only';
            exit;
        }
    }
}