<?php

namespace App\Modules\UserManagement\Models;

use App\Core\Database;
use PDO;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByUsername(string $username): ?array
    {
        log_info('Fetching user by username: ' . $username);
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.username = :username
        ");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getPermissions(int $roleId): array
    {
        log_info('Fetching permissions for role ID: ' . $roleId);
        $stmt = $this->db->prepare("
            SELECT CONCAT(p.name, '-', m.name) AS permission_name
            FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            JOIN module_permissions mp ON p.id = mp.permission_id
            JOIN modules m ON mp.module_id = m.id
            WHERE rp.role_id = :role_id
        ");
        $stmt->execute(['role_id' => $roleId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
