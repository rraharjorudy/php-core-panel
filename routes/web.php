<?php

use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Dashboard\Controllers\DashboardController;

$router = new Router();

$router->group('auth', function ($router) {
    $router->get('login', [AuthController::class, 'login']);
    $router->post('login', [AuthController::class, 'doLogin'], [CsrfMiddleware::class]);
    $router->post('logout', [AuthController::class, 'logout'], [CsrfMiddleware::class]);
});

$router->get('/', [DashboardController::class, 'index'], [AuthMiddleware::class]);
$router->get('dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);

return $router;
