<?php

namespace App\Middleware;

class AuthPermissionMiddleware
{
    protected string $permission;

    public function __construct(string $permission)
    {
        $this->permission = $permission;
    }


    public function handle(): void
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user || !isset($user['permissions']) || !is_array($user['permissions'])) {
            http_response_code(403);
            echo "Forbidden: No user session or invalid permission format.";
            exit;
        }

        if (!in_array($this->permission, $user['permissions'])) {
            http_response_code(403);
            echo "Forbidden: You do not have permission to access this page.";
            exit;
        }
    }
}
