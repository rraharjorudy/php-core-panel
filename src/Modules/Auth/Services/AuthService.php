<?php

namespace App\Modules\Auth\Services;

use App\Modules\UserManagement\Models\UserModel;

class AuthService
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(string $username, string $password): ?array
    {
        log_info('Attempting to log in user with username: ' . $username);

        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }

        $permissions = $this->userModel->getPermissions((int)$user['role_id']);

        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role_name'],
            'permissions' => $permissions,
        ];
    }
}
