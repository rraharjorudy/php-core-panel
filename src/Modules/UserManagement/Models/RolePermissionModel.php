<?php

namespace App\Modules\UserManagement\Models;

use App\Core\Database;
use PDO;

class RolePermissionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getPermissionsByRoleGrouped($role_id)
    {
        $stmt = $this->db->prepare("
            SELECT module_id, permission_id 
            FROM role_permissions 
            WHERE role_id = ?
        ");
        $stmt->execute([$role_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];

        foreach ($results as $row) {
            $modId = $row['module_id'];
            $permId = $row['permission_id'];
            if (!isset($grouped[$modId])) {
                $grouped[$modId] = [];
            }
            $grouped[$modId][] = $permId;
        }

        return $grouped;
    }

    public function deleteByRole($role_id)
    {
        $stmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = ?");
        return $stmt->execute([$role_id]);
    }

    public function insert($role_id, $module_id, $permission_id)
    {
        $stmt = $this->db->prepare("INSERT INTO role_permissions (role_id, module_id, permission_id) VALUES (?, ?, ?)");
        return $stmt->execute([$role_id, $module_id, $permission_id]);
    }
}
