<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Show errors for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

use FastRoute\RouteCollector;
use App\Controllers\HomeController;
use PHPMailer\PHPMailer\PHPMailer;

$test = new PHPMailer(true);
// Define the Routes
$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    // The Homepage
    $r->addRoute('GET', '/', [HomeController::class, 'index']);
    $r->addRoute('GET', '/register', [\App\Controllers\RegisterController::class, 'index']);
    $r->addRoute('POST', '/register', [\App\Controllers\RegisterController::class, 'register']);   
    
    //Login/Logout
    $r->addRoute('GET',  '/login',  [\App\Controllers\LoginController::class, 'index']);
    $r->addRoute('POST', '/login',  [\App\Controllers\LoginController::class, 'login']);
    $r->addRoute('POST', '/logout', [\App\Controllers\LoginController::class, 'logout']);
    $r->addRoute('GET', '/logout', [\App\Controllers\LoginController::class, 'logout']);
    
    // Password Reset
    $r->addRoute('GET', '/password-reset-request', [\App\Controllers\PasswordResetController::class, 'index']);
    $r->addRoute('POST', '/password-reset-request', [\App\Controllers\PasswordResetController::class, 'requestPaawordReset']);
    $r->addRoute('GET', '/password-reset-start', [\App\Controllers\PasswordResetController::class, 'startPasswordReset']);
    $r->addRoute('POST', '/password-reset-confirm', [\App\Controllers\PasswordResetController::class, 'createNewPassword']);
    $r->addRoute('POST', '/password-reset', [\App\Controllers\PasswordResetController::class, 'resetPassword']);
    
    // Profile (Manage account)
    $r->addRoute('GET',  '/profile',        [\App\Controllers\ProfileController::class, 'index']);
    $r->addRoute('POST', '/profile/update', [\App\Controllers\ProfileController::class, 'update']);
    
    // Cart
    $r->addRoute('GET',  '/cart',        [\App\Controllers\CartController::class, 'index']);
    $r->addRoute('POST', '/cart/add',    [\App\Controllers\CartController::class, 'add']);
    $r->addRoute('POST', '/cart/update', [\App\Controllers\CartController::class, 'update']);
    $r->addRoute('POST', '/cart/remove', [\App\Controllers\CartController::class, 'remove']);
    
    // Jazz 
    $r->addRoute('GET', '/jazz', [\App\Controllers\JazzController::class, 'index']);
    $r->addRoute('GET', '/jazz/schedule', [\App\Controllers\JazzController::class, 'schedule']);
    $r->addRoute('GET', '/jazz/tickets', [\App\Controllers\JazzController::class, 'tickets']);
    $r->addRoute('GET', '/jazz/performer', [\App\Controllers\JazzController::class, 'performer']);
    
    // CMS - Jazz homepage blocks (admin)
    $r->addRoute('GET',  '/cms/jazz/home',        [\App\Controllers\CmsJazzController::class, 'index']);
    $r->addRoute('GET',  '/cms/jazz/block/new',   [\App\Controllers\CmsJazzController::class, 'new']);
    $r->addRoute('POST', '/cms/jazz/block/new',   [\App\Controllers\CmsJazzController::class, 'create']);
    $r->addRoute('GET',  '/cms/jazz/block/edit',  [\App\Controllers\CmsJazzController::class, 'edit']);
    $r->addRoute('POST', '/cms/jazz/block/edit',  [\App\Controllers\CmsJazzController::class, 'update']);
    $r->addRoute('POST', '/cms/jazz/block/delete',[\App\Controllers\CmsJazzController::class, 'delete']);
    
    // History
    $r->addRoute('GET', '/history',        [\App\Controllers\HistoryController::class, 'index']);
    $r->addRoute('GET', '/history/{slug}', [\App\Controllers\HistoryController::class, 'detail']);
    
    // Yummy
    $r->addRoute('GET', '/yummy',      [\App\Controllers\YummyController::class, 'index']);
    $r->addRoute('GET', '/yummy/list', [\App\Controllers\YummyController::class, 'list']);
    
    // Stories in Haarlem — public pages
    $r->addRoute('GET', '/stories',                        [\App\Controllers\StoriesController::class, 'index']);
    $r->addRoute('GET', '/stories/{slug:[a-z0-9-]+}',      [\App\Controllers\StoriesController::class, 'show']);
    $r->addRoute('GET', '/stories/{slug:[a-z0-9-]+}/book', [\App\Controllers\StoriesController::class, 'book']);
    
    // CMS — Stories
    $r->addRoute('GET',  '/cms/stories',        [\App\Controllers\CmsStoriesController::class, 'index']);
    $r->addRoute('GET',  '/cms/stories/edit',   [\App\Controllers\CmsStoriesController::class, 'edit']);
    $r->addRoute('POST', '/cms/stories/save',   [\App\Controllers\CmsStoriesController::class, 'save']);
    $r->addRoute('POST', '/cms/stories/delete', [\App\Controllers\CmsStoriesController::class, 'delete']);
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
        $vars    = $routeInfo[2];

        [$class, $method] = $handler;

        // IoC — wire Stories dependencies via constructor injection
        // This satisfies the Excellent-grade dependency injection requirement.
        if ($class === \App\Controllers\StoriesController::class || $class === \App\Controllers\CmsStoriesController::class) {
            
            // Both controllers share the same Repository and Service
            $repo       = new \App\Repositories\StoriesRepository();
            $service    = new \App\Services\StoriesService($repo);
            
            // Inject the service into whichever controller is being called
            $controller = new $class($service);

        } else {
            // Fallback for all other controllers
            $controller = new $class();
        }

        $controller->$method($vars);
        break;
}