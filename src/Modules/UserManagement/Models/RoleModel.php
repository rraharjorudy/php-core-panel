<?php

namespace App\Modules\UserManagement\Models;

use App\Core\Database;
use PDO;

class RoleModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllRoles(): array
    {
        log_info('Fetching all roles');
        $stmt = $this->db->query("SELECT * FROM roles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById(int $id): ?array
    {
        log_info('Fetching role ID: ' . $id);
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createRole(array $data): bool
    {
        log_info('Creating new role: ' . $data['name']);
        $stmt = $this->db->prepare("INSERT INTO roles (name, description) VALUES (:name, :description)");
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    public function updateRole(int $id, array $data): bool
    {
        log_info('Updating role ID: ' . $id);
        $stmt = $this->db->prepare("UPDATE roles SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    public function deleteRole(int $id): bool
    {
        log_info('Deleting role ID: ' . $id);
        $stmt = $this->db->prepare("DELETE FROM roles WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
