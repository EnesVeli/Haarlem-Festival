<?php
namespace App\Controllers;

class HomeController {
    
    public function index() {
        // This is the function your router is trying to call!
        //echo "<p>The HomeController is now working correctly.</p>";
        //echo "<h1>Welcome to the Haarlem Festival!</h1>";
        require __DIR__ . '/../Views/home.php';
        
    }
}