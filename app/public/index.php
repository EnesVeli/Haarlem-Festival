<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Show errors for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

use FastRoute\RouteCollector;
use App\Controllers\HomeController;

// Define the Routes
$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    // The Homepage
    $r->addRoute('GET', '/', [HomeController::class, 'index']);
});

// Fetch method and URI from Server
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 - Page Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo '405 - Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        // Call the Controller
        [$class, $method] = $handler;
        $controller = new $class();
        $controller->$method($vars);
        break;
}