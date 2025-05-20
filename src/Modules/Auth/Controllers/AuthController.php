<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Services\AuthService;

class AuthController
{

    public function login()
    {
        return view('auth.login', [], null);
    }

    public function doLogin()
    {
        log_info('Attempting to log in user.');
        try {
            $username = trim($_POST['username']) ?? null;
            $password = $_POST['password'] ?? null;

            log_info('Username: ' . $username);
            log_info('Password: ' . $password);
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Username and password are required.';
                header('Location: ' . base_url('auth/login'));
                exit;
            }

            log_info('Creating AuthService instance.');
            $authService = new AuthService();
            $user = $authService->login($username, $password);
            if (!$user) {
                $_SESSION['error'] = 'Invalid username or password.';
                header('Location: ' . base_url('auth/login'));
                exit;
            }
            log_info('User authenticated successfully.');

            // 🛡️ Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Set user session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'permissions' => $user['permissions'],
            ];
            log_info('User session set: ' . json_encode($_SESSION['user']));

            // Redirect to dashboard
            header('Location: ' . base_url('dashboard'));
            exit;
        } catch (\Exception $e) {
            // 🐞 Log the exception if needed (e.g., to a file)
            log_error('Login Error: ' . $e->getMessage());

            $_SESSION['error'] = 'An unexpected error occurred. Please try again later.';
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . base_url('auth/login'));
        exit;
    }
}
