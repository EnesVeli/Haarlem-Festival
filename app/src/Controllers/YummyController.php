<?php
namespace App\Controllers;

use App\Services\YummyService;

class YummyController
{
    public function index()
    {
        require __DIR__ . '/../Views/yummy/home.php';
    }
}