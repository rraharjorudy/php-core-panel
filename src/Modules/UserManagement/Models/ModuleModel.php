<?php

namespace App\Modules\UserManagement\Models;

use App\Core\Database;
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

    public function getModuleById(int $id): ?array
    {
        log_info('Fetching module ID: ' . $id);
        $stmt = $this->db->prepare("SELECT * FROM modules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createModule(array $data): bool
    {
        log_info('Creating new module: ' . $data['name']);
        $stmt = $this->db->prepare("INSERT INTO modules (name, description) VALUES (:name, :description)");
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    public function updateModule(int $id, array $data): bool
    {
        log_info('Updating module ID: ' . $id);
        $stmt = $this->db->prepare("UPDATE modules SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    public function deleteModule(int $id): bool
    {
        log_info('Deleting module ID: ' . $id);
        $stmt = $this->db->prepare("DELETE FROM modules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
