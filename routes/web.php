<?php

use App\Core\Router;
use App\Middleware\CsrfMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\AuthPermissionMiddleware;

$router = new Router();

$router->group('auth', function ($router) {
    $router->get('login', [App\Modules\Auth\Controllers\AuthController::class, 'login']);
    $router->post('login', [App\Modules\Auth\Controllers\AuthController::class, 'doLogin'], [CsrfMiddleware::class]);
    $router->post('logout', [App\Modules\Auth\Controllers\AuthController::class, 'logout'], [CsrfMiddleware::class]);
});

$router->get('/', [App\Modules\Dashboard\Controllers\DashboardController::class, 'index'], [
    AuthMiddleware::class,
    AuthPermissionMiddleware::class . ':view-dashboard',
]);
$router->get('dashboard', [App\Modules\Dashboard\Controllers\DashboardController::class, 'index'], [
    AuthMiddleware::class,
    AuthPermissionMiddleware::class . ':view-dashboard',
]);

$router->group('roles', function ($router) {
    $router->get('', [\App\Modules\UserManagement\Controllers\RoleController::class, 'index'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':view-role',
    ]);

    $router->get('edit/{id}', [\App\Modules\UserManagement\Controllers\RoleController::class, 'edit'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':view-role',
    ]);

    $router->get('create', [\App\Modules\UserManagement\Controllers\RoleController::class, 'create'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':create-role',
    ]);

    $router->post('store', [\App\Modules\UserManagement\Controllers\RoleController::class, 'store'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':create-role',
        CsrfMiddleware::class,
    ]);

    $router->post('update/{id}', [\App\Modules\UserManagement\Controllers\RoleController::class, 'update'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':update-role',
        CsrfMiddleware::class,
    ]);

    $router->post('delete', [\App\Modules\UserManagement\Controllers\RoleController::class, 'delete'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':delete-role',
        CsrfMiddleware::class,
    ]);
});

$router->group('modules', function ($router) {

    $router->get('', [\App\Modules\UserManagement\Controllers\ModuleController::class, 'index'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':view-module',
    ]);

    $router->get('edit/{id}', [\App\Modules\UserManagement\Controllers\ModuleController::class, 'edit'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':view-module',
    ]);

    $router->get('create', [\App\Modules\UserManagement\Controllers\ModuleController::class, 'create'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':create-module',
    ]);

    $router->post('store', [\App\Modules\UserManagement\Controllers\ModuleController::class, 'store'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':create-module',
        CsrfMiddleware::class,
    ]);

    $router->post('update/{id}', [\App\Modules\UserManagement\Controllers\ModuleController::class, 'update'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':update-module',
        CsrfMiddleware::class,
    ]);

    $router->post('delete', [\App\Modules\UserManagement\Controllers\ModuleController::class, 'delete'], [
        AuthMiddleware::class,
        AuthPermissionMiddleware::class . ':delete-module',
        CsrfMiddleware::class,
    ]);
});

return $router;
