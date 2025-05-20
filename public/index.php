<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Bootstrap;
use App\Core\Database;

// Initialize env + session
Bootstrap::init();

// Connect to the database
Database::connect();

try {
    log_info('Application started'); 
    log_info('Request method: ' . $_SERVER['REQUEST_METHOD']);
    log_info('Request URI: ' . $_SERVER['REQUEST_URI']);
    log_info('Request headers: ' . json_encode(getallheaders()));
    log_info('Request body: ' . file_get_contents('php://input'));
    // Load routes and dispatch the request
    $router = require base_path('routes/web.php');
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code(500);
    echo "Something went wrong. Please try again later.";
    log_error($e->getMessage());
}
