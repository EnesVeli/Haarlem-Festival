<?php

require_once __DIR__ . '/../vendor/autoload.php';
define('VIEW_PATH', __DIR__ . '/../src/Views');
define('PARTIALS_PATH', VIEW_PATH . '/partials');

require_once __DIR__ . '/../src/Models/Interfaces/IBooking.php';

// Show errors for development ONLY!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

use FastRoute\RouteCollector;

// Define the Routes
$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    // The Homepage
    $r->addRoute('GET', '/',          [\App\Controllers\HomeController::class, 'index']);
    $r->addRoute('GET', '/register',  [\App\Controllers\RegisterController::class, 'index']);
    $r->addRoute('POST', '/register', [\App\Controllers\RegisterController::class, 'register']);   
    
    //Login/Logout
    $r->addRoute('GET',  '/login',  [\App\Controllers\LoginController::class, 'index']);
    $r->addRoute('POST', '/login',  [\App\Controllers\LoginController::class, 'login']);
    $r->addRoute('POST', '/logout', [\App\Controllers\LoginController::class, 'logout']);
    $r->addRoute('GET', '/logout',  [\App\Controllers\LoginController::class, 'logout']);
    
    //home CMS
    $r->addRoute('GET',  '/cms/home',              [\App\Controllers\Cms\Home\HomeCmsController::class, 'index']);
    $r->addRoute('POST', '/cms/home/save-content', [\App\Controllers\Cms\Home\HomeCmsController::class, 'saveContent']);
    $r->addRoute('POST', '/cms/home/save-event',   [\App\Controllers\Cms\Home\HomeCmsController::class, 'saveEvent']);
    $r->addRoute('POST', '/cms/home/delete-event', [\App\Controllers\Cms\Home\HomeCmsController::class, 'deleteEvent']);

    // Password Reset
    $r->addRoute('GET', '/password-reset-request',  [\App\Controllers\PasswordResetController::class, 'index']);
    $r->addRoute('POST', '/password-reset-request', [\App\Controllers\PasswordResetController::class, 'requestPasswordReset']);
    $r->addRoute('GET', '/password-reset-start',    [\App\Controllers\PasswordResetController::class, 'passwordResetVerifyEmail']);
    $r->addRoute('POST', '/password-reset-confirm', [\App\Controllers\PasswordResetController::class, 'startPasswordReset']);
    $r->addRoute('POST', '/password-reset',         [\App\Controllers\PasswordResetController::class, 'resetPassword']);
    
    // Profile (Manage account)
    $r->addRoute('GET',  '/profile',        [\App\Controllers\ProfileController::class, 'index']);
    $r->addRoute('POST', '/profile/update', [\App\Controllers\ProfileController::class, 'update']);
    
    // Jazz 
    $r->addRoute('GET', '/jazz',           [\App\Controllers\JazzController::class, 'index']);
    $r->addRoute('GET', '/jazz/schedule',  [\App\Controllers\JazzController::class, 'schedule']);
    $r->addRoute('GET', '/jazz/tickets',   [\App\Controllers\JazzController::class, 'tickets']);
    $r->addRoute('GET', '/jazz/performer', [\App\Controllers\JazzController::class, 'performer']);
    $r->addRoute('GET', '/jazz/book', [\App\Controllers\JazzController::class, 'booking']);
    $r->addRoute('POST', '/jazz/book', [\App\Controllers\JazzController::class, 'book']);

    
    // CMS - MAIN Dashboard
    $r->addRoute('GET', '/cms', [\App\Controllers\Cms\CmsDashboardController::class, 'index']);

    // Jazz CMS
    $r->addRoute('GET',  '/cms/jazz/home', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'index']);

    // jazz CMS - Hero
    $r->addRoute('GET',  '/cms/jazz/hero',        [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'hero']);
    $r->addRoute('POST', '/cms/jazz/hero/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateHero']);

    //jazz CMS - Intro
    $r->addRoute('GET',  '/cms/jazz/intro',        [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'intro']);
    $r->addRoute('POST', '/cms/jazz/intro/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateIntro']);

    //  jazz CMS -Experiences
    $r->addRoute('GET',  '/cms/jazz/experiences',        [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'experiences']);
    $r->addRoute('GET',  '/cms/jazz/experiences/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createExperience']);
    $r->addRoute('GET',  '/cms/jazz/experiences/edit',   [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editExperience']);
    $r->addRoute('POST', '/cms/jazz/experiences/store',  [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storeExperience']);
    $r->addRoute('POST', '/cms/jazz/experiences/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateExperience']);
    $r->addRoute('GET',  '/cms/jazz/experiences/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deleteExperience']);

    // jazz CMS - Performers
    $r->addRoute('GET',  '/cms/jazz/performers',        [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'performers']);
    $r->addRoute('GET',  '/cms/jazz/performers/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createPerformer']);
    $r->addRoute('GET',  '/cms/jazz/performers/edit',   [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editPerformer']);
    $r->addRoute('POST', '/cms/jazz/performers/store',  [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storePerformer']);
    $r->addRoute('POST', '/cms/jazz/performers/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updatePerformer']);
    $r->addRoute('GET',  '/cms/jazz/performers/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deletePerformer']);

    // jazz CMS - Recommendations
    $r->addRoute('GET',  '/cms/jazz/recommendations',        [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'recommendations']);
    $r->addRoute('GET',  '/cms/jazz/recommendations/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createRecommendation']);
    $r->addRoute('GET',  '/cms/jazz/recommendations/edit',   [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editRecommendation']);
    $r->addRoute('POST', '/cms/jazz/recommendations/store',  [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storeRecommendation']);
    $r->addRoute('POST', '/cms/jazz/recommendations/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateRecommendation']);
    $r->addRoute('GET',  '/cms/jazz/recommendations/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deleteRecommendation']);

    // jazz CMS - Locations
    $r->addRoute('GET',  '/cms/jazz/locations',        [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'locations']);
    $r->addRoute('GET',  '/cms/jazz/locations/create', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'createLocation']);
    $r->addRoute('GET',  '/cms/jazz/locations/edit',   [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'editLocation']);
    $r->addRoute('POST', '/cms/jazz/locations/store',  [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'storeLocation']);
    $r->addRoute('POST', '/cms/jazz/locations/update', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'updateLocation']);
    $r->addRoute('GET',  '/cms/jazz/locations/delete', [\App\Controllers\Cms\Jazz\AdminJazzController::class, 'deleteLocation']);

    // History
    $r->addRoute('GET', '/history',          [\App\Controllers\HistoryController::class, 'index']);
    $r->addRoute('GET', '/history/booking', [\App\Controllers\HistoryController::class, 'booking']);
    $r->addRoute('POST', '/history/book',    [\App\Controllers\HistoryController::class, 'book']);
    $r->addRoute('GET', '/history/{slug}',   [\App\Controllers\HistoryController::class, 'detail']);

    // History CMS
    $r->addRoute('GET',  '/cms/history',             [\App\Controllers\Cms\History\HistoryCmsController::class, 'index']);
    $r->addRoute('GET',  '/cms/history/detail/{id}', [\App\Controllers\Cms\History\HistoryCmsController::class, 'detail']);
    $r->addRoute('POST', '/cms/history/action',      [\App\Controllers\Cms\History\HistoryCmsController::class, 'action']);

    // Yummy
    $r->addRoute('GET', '/yummy',            [\App\Controllers\YummyController::class, 'index']);
    $r->addRoute('GET', '/yummy/list',       [\App\Controllers\YummyController::class, 'list']);
    $r->addRoute('GET', '/yummy/restaurant', [\App\Controllers\YummyController::class, 'restaurant']);
    $r->addRoute('GET', '/yummy/book',       [\App\Controllers\YummyController::class, 'bookingPage']);
    $r->addRoute('POST', '/yummy/book',      [\App\Controllers\YummyController::class, 'book']);
  
    // Yummy - CMS
    $r->addRoute('GET', '/cms/yummy',                           [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'index']);
    $r->addRoute('POST', '/cms/yummy/home',                     [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'editHome']);
    $r->addRoute('GET', '/cms/yummy/list',                      [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'list']);
    $r->addRoute('POST', '/cms/yummy/list',                     [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'editList']);
    $r->addRoute('GET', '/cms/yummy/restaurant-list',           [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'restaurantList']);
    $r->addRoute('GET', '/cms/yummy/restaurant',                [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'restaurant']);
    $r->addRoute('POST', '/cms/yummy/restaurant',               [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'editRestaurant']);
    $r->addRoute('POST', '/cms/yummy/restaurant/image',         [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'addImage']);
    $r->addRoute('POST', '/cms/yummy/restaurant/images/delete', [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'deleteImage']); 
    $r->addRoute('POST', '/cms/yummy/restaurant/tag',           [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'addTag']); 
    $r->addRoute('POST', '/cms/yummy/restaurant/tag/delete',    [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'deleteTag']); 
    $r->addRoute('GET', '/cms/yummy/restaurant/add',            [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'restaurantAdd']);
    $r->addRoute('POST', '/cms/yummy/restaurant/add',           [\App\Controllers\Cms\Yummy\AdminYummyController::class, 'addRestaurant']);
    
    // Tickets — main landing & per-event-type sub-pages
    $r->addRoute('GET', '/tickets',         [\App\Controllers\TicketsController::class, 'index']);
    $r->addRoute('GET', '/tickets/jazz',    [\App\Controllers\TicketsController::class, 'jazz']);
    $r->addRoute('GET', '/tickets/history', [\App\Controllers\TicketsController::class, 'history']);
    $r->addRoute('GET', '/tickets/yummy',   [\App\Controllers\TicketsController::class, 'yummy']);
    $r->addRoute('GET', '/tickets/stories', [\App\Controllers\TicketsController::class, 'stories']);

    // Stories in Haarlem — public pages
    $r->addRoute('GET', '/stories',                        [\App\Controllers\StoriesController::class, 'index']);
    $r->addRoute('GET', '/stories/{slug:[a-z0-9-]+}',      [\App\Controllers\StoriesController::class, 'show']);
    $r->addRoute('GET', '/stories/{slug:[a-z0-9-]+}/book', [\App\Controllers\StoriesController::class, 'book']);
    $r->addRoute('POST', '/stories/book/add', [\App\Controllers\StoriesController::class, 'bookAdd']);
    
    // CMS — Stories events
    $r->addRoute('GET',  '/cms/stories',        [\App\Controllers\CmsStoriesController::class, 'index']);
    $r->addRoute('GET',  '/cms/stories/edit',   [\App\Controllers\CmsStoriesController::class, 'edit']);
    $r->addRoute('POST', '/cms/stories/save',   [\App\Controllers\CmsStoriesController::class, 'save']);
    $r->addRoute('POST', '/cms/stories/delete', [\App\Controllers\CmsStoriesController::class, 'delete']);

    // ticket scanner
    $r->addRoute('GET', '/employee/scan',      [\App\Controllers\TicketScanController::class, 'index']);
    $r->addRoute('POST', '/employee/scan',     [\App\Controllers\TicketScanController::class, 'scan']);
    $r->addRoute('GET', '/ticket/qr/{id:\d+}', [\App\Controllers\TicketQrController::class, 'show']);

    // CMS — Stories Homepage content
    $r->addRoute('GET',  '/cms/stories/homepage', [\App\Controllers\CmsStoriesHomepageController::class, 'edit']);
    $r->addRoute('POST', '/cms/stories/homepage', [\App\Controllers\CmsStoriesHomepageController::class, 'update']);
    
    // Cart
    $r->addRoute('GET',  '/cart',         [\App\Controllers\CartController::class, 'index']);
    $r->addRoute('POST', '/cart/add',     [\App\Controllers\CartController::class, 'add']);
    $r->addRoute('POST', '/cart/update',  [\App\Controllers\CartController::class, 'update']);  
    $r->addRoute('POST', '/cart/remove',  [\App\Controllers\CartController::class, 'remove']);
    $r->addRoute('GET',  '/checkout',     [\App\Controllers\CartController::class, 'checkout']);
    $r->addRoute('GET',  '/program',      [\App\Controllers\CartController::class, 'program']);
    $r->addRoute('GET',  '/payment',      [\App\Controllers\CartController::class, 'payment']);
    $r->addRoute('GET',  '/payment/fail', [\App\Controllers\CartController::class, 'paymentFail']);
    $r->addRoute('POST',  '/payment/notpaid/cancel', [\App\Controllers\CartController::class, 'paymentNotPaidCancel']);
    $r->addRoute('POST',  '/payment/notpaid/pay', [\App\Controllers\CartController::class, 'paymentNotPaidPay']);
    //$r->addRoute('POST',  '/payment/paid/cancel', [\App\Controllers\CartController::class, 'paymentPaidCancel']);
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

        $controller = new $class();  

        $controller->$method($vars);
        break;
}