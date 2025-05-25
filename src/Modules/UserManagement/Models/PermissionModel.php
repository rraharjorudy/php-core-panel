<?php

namespace App\Modules\UserManagement\Models;

use App\Core\Database;
use PDO;

class PermissionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllPermissions()
    {
        log_info('Fetching all permissions');
        return $this->db->query("SELECT * FROM permissions")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionsByModuleId($moduleId)
    {
        log_info('Fetching permissions for module ID: ' . $moduleId);
        $stmt = $this->db->prepare("SELECT permission_id FROM module_permissions WHERE module_id = :id");
        $stmt->execute(['id' => $moduleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
