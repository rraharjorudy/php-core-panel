<?php

namespace App\Modules\UserManagement\Models;

use App\Core\Database;
use BcMath\Number;
use PDO;

class ModuleModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllModules(): array
    {
        log_info('Fetching all modules');
        $stmt = $this->db->query("SELECT * FROM modules");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionsPerModule(): array
    {
        $result = $this->db->query("SELECT * FROM module_permissions")->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($result as $row) {
            $map[$row['module_id']][] = $row['permission_id'];
        }
        return $map;
    }


    public function getModuleById(int $id): ?array
    {
        log_info('Fetching module ID: ' . $id);
        $stmt = $this->db->prepare("SELECT * FROM modules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getParentModule(): ?array
    {
        log_info('Fetching parent module');
        $stmt = $this->db->query("SELECT id, name FROM modules WHERE parent_id IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createModule(array $data): int
    {
        log_info('Creating new module: ' . $data['name']);
        $stmt = $this->db->prepare("INSERT INTO modules (name, description, parent_id) VALUES (:name, :description, :parent_id)");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => $data['parent_id']
        ]);
        return $this->db->lastInsertId();
    }

    public function createModulePermission($module_id, $permission_id): bool
    {
        $stmt = $this->db->prepare("INSERT INTO module_permissions (module_id, permission_id) VALUES (?, ?)");
        return $stmt->execute([$module_id, $permission_id]);
    }

    public function updateModule(int $id, array $data): bool
    {
        log_info('Updating module ID: ' . $id);
        $stmt = $this->db->prepare("UPDATE modules SET name = :name, description = :description, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => $data['parent_id']
        ]);
    }

    public function updateModulePermissions($moduleId, $permissions): void
    {
        log_info('Updating permissions for module ID: ' . $moduleId);

        $stmtDeleted = $this->db->prepare("DELETE FROM module_permissions WHERE module_id = ?");
        $stmtDeleted->execute([$moduleId]);

        $stmtInsert = $this->db->prepare("INSERT INTO module_permissions (module_id, permission_id) VALUES (?, ?)");

        // Insert new ones
        foreach ($permissions as $permissionId) {
            $stmtInsert->execute([$moduleId, $permissionId]);
        }
    }


    public function deleteModule(int $id): bool
    {
        log_info('Deleting module ID: ' . $id);
        $stmt = $this->db->prepare("DELETE FROM modules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function buildModuleTree(array $modules, $parentId = null): array
    {
        $branch = [];

        foreach ($modules as $module) {
            if ($module['parent_id'] == $parentId) {
                $children = $this->buildModuleTree($modules, $module['id']);
                if ($children) {
                    $module['children'] = $children;
                }
                $branch[] = $module;
            }
        }

        return $branch;
    }
}
