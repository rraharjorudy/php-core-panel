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
        log_info('Controller Fetching all roles');
        $data = $this->model->getAllRoles();

        return view('user_management.roles.index', ['data' => $data]);
    }

    public function create()
    {
        log_info('Controller Creating new role');
        return view('user_management.roles.create');
    }

    public function store()
    {
        log_info('Controller Storing new role');
        $name = trim($_POST['name']) ?? null;
        $description = $_POST['description'] ?? null;

        if (empty($name) || empty($description)) {
            $_SESSION['error'] = 'Name and description are required.';
            header('Location: ' . base_url('user_management/roles/create'));
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
            header('Location: ' . base_url('user_management/roles'));
            exit;
        } else {
            $_SESSION['error'] = 'Failed to create role.';
            header('Location: ' . base_url('user_management/roles/create'));
            exit;
        }
    }

    public function edit($id)
    {
        log_info('Controller Editing role ID: ' . $id);
        $role = $this->model->getRoleById($id);
        if (!$role) {
            $_SESSION['error'] = 'Role not found.';
            header('Location: ' . base_url('user_management/roles'));
            exit;
        }

        return view('user_management.roles.edit', ['role' => $role]);
    }

    public function update($id)
    {
        log_info('Controller Updating role ID: ' . $id);
        $name = trim($_POST['name']) ?? null;
        $description = $_POST['description'] ?? null;

        if (empty($name) || empty($description)) {
            $_SESSION['error'] = 'Name and description are required.';
            header('Location: ' . base_url('user_management/roles/edit/' . $id));
            exit;
        }

        log_info('Updating role ID: ' . $id);
        log_info('New Name: ' . $name);
        log_info('New Description: ' . $description);

        $roleData = [
            'name' => $name,
            'description' => $description
        ];

        if ($this->model->updateRole($id, $roleData)) {
            $_SESSION['success'] = 'Role updated successfully.';
            header('Location: ' . base_url('user_management/roles'));
            exit;
        } else {
            $_SESSION['error'] = 'Failed to update role.';
            header('Location: ' . base_url('user_management/roles/edit/' . $id));
            exit;
        }
    }

    public function delete($id)
    {
        log_info('Controller Deleting role ID: ' . $id);
        if ($this->model->deleteRole($id)) {
            $_SESSION['success'] = 'Role deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete role.';
        }

        header('Location: ' . base_url('user_management/roles'));
        exit;
    }
}
