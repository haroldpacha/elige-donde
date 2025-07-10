<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class Auth extends BaseController
{
    protected $adminUserModel;

    public function __construct()
    {
        $this->adminUserModel = new AdminUserModel();
    }

    // Show login form
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $data = [
            'title' => 'Iniciar Sesión - Admin Elige Donde'
        ];

        return view('admin/auth/login', $data);
    }

    // Process login
    public function processLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->adminUserModel->authenticate($email, $password);

        if ($user) {
            // Set session data
            $sessionData = [
                'admin_id' => $user['id'],
                'admin_email' => $user['email'],
                'admin_name' => $user['first_name'] . ' ' . $user['last_name'],
                'admin_role' => $user['role'],
                'admin_logged_in' => true
            ];

            session()->set($sessionData);

            // Set remember me cookie if requested
            if ($remember) {
                $this->response->setCookie('admin_remember', $user['id'], 3600 * 24 * 30); // 30 days
            }

            return redirect()->to('/admin')->with('success', 'Bienvenido al panel administrativo');
        } else {
            return redirect()->back()->withInput()->with('error', 'Credenciales incorrectas');
        }
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        $this->response->deleteCookie('admin_remember');

        return redirect()->to('/admin/login')->with('success', 'Sesión cerrada exitosamente');
    }

    // Forgot password form
    public function forgotPassword()
    {
        $data = [
            'title' => 'Recuperar Contraseña - Admin Elige Donde'
        ];

        return view('admin/auth/forgot_password', $data);
    }

    // Process forgot password
    public function processForgotPassword()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');

        // Check if user exists
        $user = $this->adminUserModel->where('email', $email)->where('is_active', 1)->first();

        if ($user) {
            // Generate reset token (you would implement this)
            $token = bin2hex(random_bytes(32));

            // Here you would save the token and send email
            // For now, just show success message
            return redirect()->to('/admin/login')->with('success', 'Se ha enviado un enlace de recuperación a tu email');
        } else {
            return redirect()->back()->with('error', 'No se encontró una cuenta con ese email');
        }
    }

    // Check if user is authenticated (middleware function)
    public function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            // Check remember me cookie
            $rememberCookie = $this->request->getCookie('admin_remember');

            if ($rememberCookie) {
                $user = $this->adminUserModel->getUserById($rememberCookie);

                if ($user && $user['is_active']) {
                    // Re-establish session
                    $sessionData = [
                        'admin_id' => $user['id'],
                        'admin_email' => $user['email'],
                        'admin_name' => $user['first_name'] . ' ' . $user['last_name'],
                        'admin_role' => $user['role'],
                        'admin_logged_in' => true
                    ];

                    session()->set($sessionData);
                    return true;
                }
            }

            return redirect()->to('/admin/login')->with('error', 'Debes iniciar sesión para acceder al panel administrativo');
        }

        return true;
    }

    // Profile page
    public function profile()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $userId = session()->get('admin_id');
        $user = $this->adminUserModel->getUserById($userId);

        $data = [
            'title' => 'Mi Perfil - Admin Elige Donde',
            'user' => $user
        ];

        return view('admin/auth/profile', $data);
    }

    // Update profile
    public function updateProfile()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'required|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'email' => 'required|valid_email|max_length[100]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userId = session()->get('admin_id');
        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email')
        ];

        // Check if email is unique (excluding current user)
        $existingUser = $this->adminUserModel->where('email', $updateData['email'])
                                           ->where('id !=', $userId)
                                           ->first();

        if ($existingUser) {
            return redirect()->back()->withInput()->with('error', 'El email ya está en uso');
        }

        if ($this->adminUserModel->updateUser($userId, $updateData)) {
            // Update session data
            session()->set('admin_name', $updateData['first_name'] . ' ' . $updateData['last_name']);
            session()->set('admin_email', $updateData['email']);

            return redirect()->back()->with('success', 'Perfil actualizado exitosamente');
        } else {
            return redirect()->back()->with('error', 'Error al actualizar el perfil');
        }
    }

    // Change password
    public function changePassword()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $validation = \Config\Services::validation();

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        $userId = session()->get('admin_id');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Get current user data
        $user = $this->adminUserModel->find($userId);

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'La contraseña actual es incorrecta');
        }

        if ($this->adminUserModel->changePassword($userId, $newPassword)) {
            return redirect()->back()->with('success', 'Contraseña cambiada exitosamente');
        } else {
            return redirect()->back()->with('error', 'Error al cambiar la contraseña');
        }
    }
}
