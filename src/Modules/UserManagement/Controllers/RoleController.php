<?php

namespace App\Modules\UserManagement\Controllers;

class RoleController
{
    protected $model;

    public function __construct()
    {
        $this->model = new \App\Modules\UserManagement\Models\RoleModel();
    }

    public function index()
    {
        try {
            log_info('Controller Fetching all roles');
            $data = $this->model->getAllRoles();

            return view('user_management.roles.index', ['roles' => $data]);
        } catch (\Throwable $e) {
            log_error('Failed to fetch roles: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while fetching roles.';
            header('Location: ' . base_url('/'));
            exit;
        }
    }

    public function create()
    {
        try {
            log_info('Controller Creating new role');
            return view('user_management.roles.create');
        } catch (\Throwable $e) {
            log_error('Failed to load create form: ' . $e->getMessage());
            $_SESSION['error'] = 'Unable to load create form.';
            header('Location: ' . base_url('roles'));
            exit;
        }
    }

    public function store()
    {
        try {
            log_info('Controller Storing new role');
            $name = isset($_POST['name']) ? strtolower(trim($_POST['name'])) : null;
            $description = $_POST['description'] ?? null;

            if (empty($name) || empty($description)) {
                $_SESSION['error'] = 'Name and description are required.';
                header('Location: ' . base_url('roles/create'));
                exit;
            }

            log_info('Creating new role: ' . $name);
            log_info('Description: ' . $description);

            $roleData = [
                'name' => $name,
                'description' => $description
            ];

            if ($this->model->createRole($roleData)) {
                $_SESSION['success'] = 'Role created successfully.';
                header('Location: ' . base_url('roles'));
            } else {
                $_SESSION['error'] = 'Failed to create role.';
                header('Location: ' . base_url('roles/create'));
            }
        } catch (\Throwable $e) {
            log_error('Error storing new role: ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while creating the role.';
            header('Location: ' . base_url('roles/create'));
        }
        exit;
    }

    public function edit($id)
    {
        try {
            log_info('Controller Editing role ID: ' . $id);
            $role = $this->model->getRoleById($id);
            if (!$role) {
                $_SESSION['error'] = 'Role not found.';
                header('Location: ' . base_url('roles'));
                exit;
            }

            return view('user_management.roles.edit', ['role' => $role]);
        } catch (\Throwable $e) {
            log_error('Error editing role ID ' . $id . ': ' . $e->getMessage());
            $_SESSION['error'] = 'Unable to load role for editing.';
            header('Location: ' . base_url('roles'));
            exit;
        }
    }

    public function update($id)
    {
        try {
            log_info('Controller Updating role ID: ' . $id);
            $name = isset($_POST['name']) ? strtolower(trim($_POST['name'])) : null;
            $description = $_POST['description'] ?? null;

            if (empty($name) || empty($description)) {
                $_SESSION['error'] = 'Name and description are required.';
                header('Location: ' . base_url('roles/edit/' . $id));
                exit;
            }

            $roleData = [
                'name' => $name,
                'description' => $description
            ];

            if ($this->model->updateRole($id, $roleData)) {
                $_SESSION['success'] = 'Role updated successfully.';
                header('Location: ' . base_url('roles'));
            } else {
                $_SESSION['error'] = 'Failed to update role.';
                header('Location: ' . base_url('roles/edit/' . $id));
            }
        } catch (\Throwable $e) {
            log_error('Error updating role ID ' . $id . ': ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while updating the role.' . $e->getMessage();
            header('Location: ' . base_url('roles/edit/' . $id));
        }
        exit;
    }

    public function delete()
    {
        try {
            log_info('Controller Deleting role');

            $id = $_POST['id'] ?? null;

            if (empty($id)) {
                $_SESSION['error'] = 'Role ID is required.';
                header('Location: ' . base_url('roles'));
                exit;
            }

            log_info('Controller Deleting role ID: ' . $id);
            if ($this->model->deleteRole($id)) {
                $_SESSION['success'] = 'Role deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete role.';
            }
        } catch (\Throwable $e) {
            log_error('Error deleting role ID ' . ($id ?? 'unknown') . ': ' . $e->getMessage());
            $_SESSION['error'] = 'Something went wrong while deleting the role.' . $e->getMessage();
        }

        header('Location: ' . base_url('roles'));
        exit;
    }
}
