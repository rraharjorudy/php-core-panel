<?php

namespace App\Modules\UserManagement\Controllers;

class ModuleController
{

    protected $model;
    protected $permissionModel;

    public function __construct()
    {
        $this->model = new \App\Modules\UserManagement\Models\ModuleModel();
        $this->permissionModel = new \App\Modules\UserManagement\Models\PermissionModel();
    }

    public function index()
    {
        try {
            log_info('Controller Fetching all modules');
            $data = $this->model->getAllModules();
            $tree = $this->model->buildModuleTree($data);

            return view('user_management.modules.index', ['modules' => $tree]);
        } catch (\Throwable $e) {
            log_error('Failed to fetch modules: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while fetching modules.';
            header('Location: ' . base_url('/'));
            exit;
        }
    }

    public function create()
    {
        try {
            log_info('Controller Creating new module');
            $data = $this->model->getParentModule();
            $permissions = $this->permissionModel->getAllPermissions();

            return view('user_management.modules.create', [
                'parent_module' => $data,
                'permissions' => $permissions
            ]);
        } catch (\Throwable $e) {
            log_error('Failed to load create form: ' . $e->getMessage());
            $_SESSION['error'] = 'Unable to load create form.';
            header('Location: ' . base_url('modules'));
            exit;
        }
    }

    public function store()
    {
        try {
            log_info('Controller Storing new module');
            $name = isset($_POST['name']) ? strtolower(str_replace(' ', '_', trim($_POST['name']))) : null;
            $description = $_POST['description'] ?? null;
            $parent_id = isset($_POST['parent_id']) && is_numeric($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
            $permissions = $_POST['permissions'] ?? [];

            if (empty($name) || empty($description)) {
                $_SESSION['error'] = 'Name and description are required.';
                header('Location: ' . base_url('modules/create'));
                exit;
            }

            log_info('Creating new module: ' . $name);
            log_info('Description: ' . $description);
            log_info('Parent ID: ' . $parent_id);
            log_info('Permissions: ' . implode(', ', $permissions));

            $moduleData = [
                'name' => $name,
                'description' => $description,
                'parent_id' => $parent_id
            ];

            $module_id = $this->model->createModule($moduleData);

            if ($module_id === 0) {
                $_SESSION['error'] = 'Failed to create module.';
                header('Location: ' . base_url('modules/create'));
                exit;
            }

            if (!empty($permissions)) {
                foreach ($permissions as $perm_id) {
                    $this->model->createModulePermission($module_id, $perm_id);
                }
            }

            $_SESSION['success'] = 'Module created successfully.';
            header('Location: ' . base_url('modules'));
            exit;
        } catch (\Throwable $e) {
            log_error('Failed to store module: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while storing the module.' . $e->getMessage();
            header('Location: ' . base_url('modules/create'));
            exit;
        }
    }

    public function edit($id)
    {
        try {
            log_info('Controller Editing module ID: ' . $id);
            $module = $this->model->getModuleById($id);
            $parentModules = $this->model->getParentModule();
            $allPermissions = $this->permissionModel->getAllPermissions();
            $assignedPermissions = $this->permissionModel->getPermissionsByModuleId($id);

            if (!$module) {
                $_SESSION['error'] = 'Module not found.';
                header('Location: ' . base_url('modules'));
                exit;
            }

            return view('user_management.modules.edit', [
                'module' => $module,
                'parent_module' => $parentModules,
                'permissions' => $allPermissions,
                'assigned_permissions' => $assignedPermissions,
            ]);
        } catch (\Throwable $e) {
            log_error('Failed to load edit form: ' . $e->getMessage());
            $_SESSION['error'] = 'Unable to load edit form.';
            header('Location: ' . base_url('modules'));
            exit;
        }
    }

    public function update($id)
    {
        try {
            log_info('Controller Updating module ID: ' . $id);
            $name = isset($_POST['name']) ? strtolower(str_replace(' ', '_', trim($_POST['name']))) : null;
            $description = $_POST['description'] ?? null;
            $parent_id = isset($_POST['parent_id']) && is_numeric($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
            $permissions = $_POST['permissions'] ?? [];

            if (empty($name) || empty($description)) {
                $_SESSION['error'] = 'Name and description are required.';
                header('Location: ' . base_url('modules/edit/' . $id));
                exit;
            }

            log_info('Updating module ID: ' . $id);
            log_info('New Name: ' . $name);
            log_info('New Description: ' . $description);
            log_info('New Parent ID: ' . $parent_id);

            $moduleData = [
                'name' => $name,
                'description' => $description,
                'parent_id' => $parent_id
            ];

            if (!$this->model->updateModule($id, $moduleData)) {
                $_SESSION['error'] = 'Failed to update module.';
                header('Location: ' . base_url('modules/edit/' . $id));
                exit;
            }

            $this->model->updateModulePermissions($id, $permissions); // Clear + re-insert

            $_SESSION['success'] = 'Module updated successfully.';
            header('Location: ' . base_url('modules'));
            exit;
        } catch (\Throwable $e) {
            log_error('Failed to update module: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while updating the module.';
            header('Location: ' . base_url('modules/edit/' . $id));
            exit;
        }
    }

    public function delete()
    {
        try {
            log_info('Controller Deleting module');
            $id = $_POST['id'] ?? null;

            if (empty($id)) {
                $_SESSION['error'] = 'Module ID is required.';
                header('Location: ' . base_url('modules'));
                exit;
            }

            log_info('Deleting module ID: ' . $id);

            if (!$this->model->getModuleById($id)) {
                $_SESSION['error'] = 'Module not found.';
                header('Location: ' . base_url('modules'));
                exit;
            }

            if (!$this->model->deleteModule($id)) {
                $_SESSION['error'] = 'Failed to delete module.';
                header('Location: ' . base_url('modules'));
                exit;
            }

            $_SESSION['success'] = 'Module deleted successfully.';
            header('Location: ' . base_url('modules'));
            exit;
        } catch (\Throwable $e) {
            log_error('Failed to delete module: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while deleting the module.';
            header('Location: ' . base_url('modules'));
            exit;
        }
    }
}
