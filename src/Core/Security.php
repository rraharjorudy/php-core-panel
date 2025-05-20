<?php

namespace App\Core;

class Security
{
    public static function generateCsrfToken()
    {
        if (!session_id()) session_start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function getCsrfField(): string
    {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validateCsrfToken($token): bool
    {
        return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
    }

    public static function escapeOutput($value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
