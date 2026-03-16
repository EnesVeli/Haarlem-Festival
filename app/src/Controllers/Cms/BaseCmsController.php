<?php

namespace App\Controllers\Cms;

use App\Framework\Session;

abstract class BaseCmsController
{
    public function __construct()
    {
        $this->requireAdmin();
    }

    protected function requireAdmin(): void
    {
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        if (!Session::isAdmin()) {
            http_response_code(403);
            echo '403 - Admin only';
            exit;
        }
    }
}