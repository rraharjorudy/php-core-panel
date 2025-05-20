<?php

namespace App\Middleware;

use App\Core\Security;

class CsrfMiddleware
{
    public static function handle()
    {
        if (!session_id()) session_start();

        $method = $_SERVER['REQUEST_METHOD'];
        $protectedMethods = ['POST', 'PUT', 'DELETE'];

        if (in_array($method, $protectedMethods)) {
            $token = $_POST['_csrf_token'] ?? $_GET['_csrf_token'] ?? '';

            if (!\App\Core\Security::validateCsrfToken($token)) {
                http_response_code(419);
                echo "Invalid CSRF token.";
                exit;
            }
        }
    }
}
