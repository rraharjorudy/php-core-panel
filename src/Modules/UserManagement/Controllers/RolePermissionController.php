<?php

namespace App\Modules\UserManagement\Controllers;

class RolePermissionController
{

    protected $roleModel;
    protected $permissionModel;
    protected $moduleModel;
    protected $rolePermissionModel;

    public function __construct()
    {
        $this->roleModel = new \App\Modules\UserManagement\Models\RoleModel();
        $this->permissionModel = new \App\Modules\UserManagement\Models\PermissionModel();
        $this->moduleModel = new \App\Modules\UserManagement\Models\ModuleModel();
        $this->rolePermissionModel = new \App\Modules\UserManagement\Models\RolePermissionModel();
    }

    public function index()
    {

        try {
            log_info('Controller Fetching all modules');

            $roles = $this->roleModel->getAllRoles();
            $modules = $this->moduleModel->getAllModules();
            $permissions = $this->permissionModel->getAllPermissions();

            // Get current role_id from GET param
            $roleId = $_GET['role_id'] ?? null;

            $rolePermissionsMap = $roleId ? $this->rolePermissionModel->getPermissionsByRoleGrouped($roleId) : [];

            $modulePermissionsMap = $this->moduleModel->getPermissionsPerModule();

            $groupedModules = [];
            foreach ($modules as $mod) {
                if ($mod['parent_id'] === null) {
                    $groupedModules[$mod['id']] = ['parent' => $mod, 'children' => []];
                }
            }

            foreach ($modules as $mod) {
                if ($mod['parent_id'] !== null && isset($groupedModules[$mod['parent_id']])) {
                    $groupedModules[$mod['parent_id']]['children'][] = $mod;
                }
            }

            // echo "<pre>";
            // print_r($rolePermissionsMap);
            // echo "</pre>";
            // exit;



            return view('user_management.role_permissions.index', [
                'role_id' => $roleId,
                'roles' => $roles,
                'permissions' => $permissions,
                'groupedModules' => $groupedModules,
                'modulePermissionsMap' => $modulePermissionsMap,
                'rolePermissionsMap' => $rolePermissionsMap,
            ]);
        } catch (\Throwable $e) {
            log_error('Failed to fetch modules: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while fetching modules.' .  $e->getMessage();
            header('Location: ' . base_url('/'));
            exit;
        }
    }

    public function update()
    {
        log_info('Controller Updating role permissions');
        try {

            $role_id = $_POST['role_id'] ?? null;
            $permissions = $_POST['permissions'] ?? [];

            if (!$role_id) {
                $_SESSION['error'] = "Role is required.";
                header("Location: " . base_url("role-permissions"));
                exit;
            }

            $this->rolePermissionModel->deleteByRole($role_id);

            foreach ($permissions as $module_id => $perm_ids) {
                foreach ($perm_ids as $perm_id) {
                    $this->rolePermissionModel->insert($role_id, $module_id, $perm_id);
                }
            }

            $_SESSION['success'] = "Permissions updated successfully.";
            header("Location: " . base_url("role-permissions?role_id=$role_id"));
            exit;
        } catch (\Throwable $e) {
            log_error('Failed Updating role permissions: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while Updating role permissions.' .  $e->getMessage();
            header('Location: ' . base_url('/role-permissions'));
            exit;
        }
    }
}
