<?php

use App\Core\Security;

if (!function_exists('base_path')) {
    /**
     * Returns the base path of the application.
     *
     * @param string $path Optional path to append to the base path.
     * @return string The full base path.
     */
    function base_path($path = '')
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
    }
}

if (!function_exists('view')) {
    /**
     * Renders a view file with the given data and layout.
     *
     * @param string $name The name of the view file (without extension).
     * @param array $data An associative array of data to be passed to the view.
     * @param string|null $layout The name of the layout file (without extension) or null for no layout.
     */
    function view(string $name, array $data = [], $layout = 'default')
    {
        $relativePath = str_replace('.', '/', $name);
        $viewPath = base_path("resources/views/pages/{$relativePath}.php");

        extract($data);

        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $content = ob_get_clean();

            if ($layout !== null) {
                $layoutPath = base_path("resources/views/layouts/{$layout}.php");
                if (file_exists($layoutPath)) {
                    include $layoutPath;
                } else {
                    http_response_code(500);
                    echo "Layout not found: " . htmlspecialchars($layoutPath);
                }
            } else {
                echo $content; // No layout, render raw content
            }
        } else {
            http_response_code(500);
            echo "View not found: " . htmlspecialchars($viewPath);
        }
    }
}

if (!function_exists('start_section')) {
    function start_section($name)
    {
        global $__sections;
        $__sections[$name] = '';
        ob_start();
    }
}

if (!function_exists('end_section')) {
    function end_section()
    {
        global $__sections;
        $__sections[array_key_last($__sections)] = ob_get_clean();
    }
}

if (!function_exists('yield_section')) {
    function yield_section($name)
    {
        global $__sections;
        echo $__sections[$name] ?? '';
    }
}

if (!function_exists('base_url')) {
    /**
     * Generates a base URL for the application.
     *
     * @param string $path The path to append to the base URL.
     * @return string The full base URL.
     */
    function base_url($path = '')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $domain = $_SERVER['HTTP_HOST'];
        return "{$protocol}://{$domain}/" . ltrim($path, '/');
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generates a CSRF token for form submissions.
     *
     * @return string The CSRF token.
     */
    function csrf_field(): string
    {
        return Security::getCsrfField();
    }
}

if (!function_exists('escape')) {
    /**
     * Escapes a value for safe output.
     *
     * @param mixed $value The value to escape.
     * @return bool The escaped value.
     */
    function escape($value): bool
    {
        return Security::escapeOutput($value);
    }
}

if (!function_exists('flash')) {
    /**
     * Retrieves and clears a flash message from the session.
     *
     * @param string $key The key of the flash message.
     * @return string|null The flash message or null if not set.
     */
    function flash(string $key)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION[$key])) {
            $message = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $message;
        }
        return null;
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Checks if the user has a specific permission.
     *
     * @param string $permission The permission to check.
     * @return bool True if the user has the permission, false otherwise.
     */
    function hasPermission(string $permission): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION['user']['permissions'])) {
            return in_array($permission, $_SESSION['user']['permissions']);
        }
        return false;
    }
}
