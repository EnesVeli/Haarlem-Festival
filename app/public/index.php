<?php
require_once __DIR__ . '/../vendor/autoload.php';
define('VIEW_PATH', __DIR__ . '/../src/Views');
define('PARTIALS_PATH', VIEW_PATH . '/partials');
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
    $r->addRoute('POST', '/password-reset-request', [\App\Controllers\PasswordResetController::class, 'requestPasswordReset']);
    $r->addRoute('GET', '/password-reset-start', [\App\Controllers\PasswordResetController::class, 'passwordResetVerifyEmail']);
    $r->addRoute('POST', '/password-reset-confirm', [\App\Controllers\PasswordResetController::class, 'startPasswordReset']);
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
    
    // Jazz CMS
    $r->addRoute('GET',  '/cms/jazz/home', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'index']);
    // jazz CMS - Hero
    $r->addRoute('GET',  '/cms/jazz/hero', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'hero']);
    $r->addRoute('POST', '/cms/jazz/hero/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateHero']);
    //jazz CMS - Intro
    $r->addRoute('GET',  '/cms/jazz/intro', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'intro']);
    $r->addRoute('POST', '/cms/jazz/intro/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateIntro']);
    //  jazz CMS -Experiences
    $r->addRoute('GET',  '/cms/jazz/experiences', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'experiences']);
    $r->addRoute('GET',  '/cms/jazz/experiences/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createExperience']);
    $r->addRoute('GET',  '/cms/jazz/experiences/edit', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editExperience']);
    $r->addRoute('POST', '/cms/jazz/experiences/store', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storeExperience']);
    $r->addRoute('POST', '/cms/jazz/experiences/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateExperience']);
    $r->addRoute('GET',  '/cms/jazz/experiences/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deleteExperience']);
    // jazz CMS - Performers
    $r->addRoute('GET',  '/cms/jazz/performers', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'performers']);
    $r->addRoute('GET',  '/cms/jazz/performers/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createPerformer']);
    $r->addRoute('GET',  '/cms/jazz/performers/edit', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editPerformer']);
    $r->addRoute('POST', '/cms/jazz/performers/store', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storePerformer']);
    $r->addRoute('POST', '/cms/jazz/performers/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updatePerformer']);
    $r->addRoute('GET',  '/cms/jazz/performers/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deletePerformer']);
    // jazz CMS - Recommendations
    $r->addRoute('GET',  '/cms/jazz/recommendations', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'recommendations']);
    $r->addRoute('GET',  '/cms/jazz/recommendations/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createRecommendation']);
    $r->addRoute('GET',  '/cms/jazz/recommendations/edit', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editRecommendation']);
    $r->addRoute('POST', '/cms/jazz/recommendations/store', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storeRecommendation']);
    $r->addRoute('POST', '/cms/jazz/recommendations/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateRecommendation']);
    $r->addRoute('GET',  '/cms/jazz/recommendations/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deleteRecommendation']);
    // jazz CMS - Locations
    $r->addRoute('GET',  '/cms/jazz/locations', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'locations']);
    $r->addRoute('GET',  '/cms/jazz/locations/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createLocation']);
    $r->addRoute('GET',  '/cms/jazz/locations/edit', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editLocation']);
    $r->addRoute('POST', '/cms/jazz/locations/store', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storeLocation']);
    $r->addRoute('POST', '/cms/jazz/locations/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateLocation']);
    $r->addRoute('GET',  '/cms/jazz/locations/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deleteLocation']);
    // History
    $r->addRoute('GET', '/history',        [\App\Controllers\HistoryController::class, 'index']);
    $r->addRoute('GET', '/history/{slug}', [\App\Controllers\HistoryController::class, 'detail']);
    
    // Yummy
    $r->addRoute('GET', '/yummy',      [\App\Controllers\YummyController::class, 'index']);
    $r->addRoute('GET', '/yummy/list', [\App\Controllers\YummyController::class, 'list']);
    $r->addRoute('GET', '/yummy/restaurant', [\App\Controllers\YummyController::class, 'restaurant']);
    $r->addRoute('GET', '/yummy/book', [\App\Controllers\YummyController::class, 'bookingPage']);
    $r->addRoute('POST', '/yummy/book', [\App\Controllers\YummyController::class, 'book']);
    // Yummy - CMS
    $r->addRoute('GET', '/cms/yummy/',      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'index']);
    $r->addRoute('POST', '/cms/yummy/home',      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'editHome']);
    $r->addRoute('GET', '/cms/yummy/list',      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'list']);
    $r->addRoute('POST', '/cms/yummy/list',      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'editList']);
    $r->addRoute('GET', '/cms/yummy/restaurant-list',      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'restaurantList']);
    $r->addRoute('GET', '/cms/yummy/restaurant',      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'restaurant']);
    
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