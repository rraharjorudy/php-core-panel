<?php

namespace App\Core;

use Dotenv\Dotenv;

class Bootstrap
{
    public static function init(): void
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Load .env variables if not already loaded
        if (!isset($_ENV['DB_DRIVER'])) {
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();
        }
    }
}
