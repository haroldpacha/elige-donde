<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'password', 'role', 'is_active', 'last_login'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'first_name' => 'required|max_length[50]',
        'last_name' => 'required|max_length[50]',
        'email' => 'required|valid_email|max_length[100]|is_unique[admin_users.email]',
        'password' => 'required|min_length[6]',
        'role' => 'permit_empty|in_list[admin,manager,agent]'
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Hash password before saving
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // Authenticate user
    public function authenticate($email, $password)
    {
        $user = $this->where('email', $email)
                    ->where('is_active', 1)
                    ->first();

        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

            // Remove password from return data
            unset($user['password']);
            return $user;
        }

        return false;
    }

    // Get all active users
    public function getActiveUsers()
    {
        return $this->select('id, first_name, last_name, email, role, last_login, created_at')
                   ->where('is_active', 1)
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    // Create new admin user
    public function createUser($data)
    {
        return $this->insert($data);
    }

    // Update user (excluding password if not provided)
    public function updateUser($id, $data)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->update($id, $data);
    }

    // Change password
    public function changePassword($userId, $newPassword)
    {
        return $this->update($userId, ['password' => $newPassword]);
    }

    // Get user by ID (without password)
    public function getUserById($id)
    {
        return $this->select('id, first_name, last_name, email, role, is_active, last_login, created_at')
                   ->find($id);
    }

    // Get users by role
    public function getUsersByRole($role)
    {
        return $this->select('id, first_name, last_name, email, role, last_login')
                   ->where('role', $role)
                   ->where('is_active', 1)
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    // Deactivate user instead of delete
    public function deactivateUser($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }

    // Activate user
    public function activateUser($id)
    {
        return $this->update($id, ['is_active' => 1]);
    }

    // Get dashboard stats
    public function getDashboardStats()
    {
        $stats = [
            'total_users' => $this->where('is_active', 1)->countAllResults(),
            'admin_users' => $this->where(['role' => 'admin', 'is_active' => 1])->countAllResults(),
            'manager_users' => $this->where(['role' => 'manager', 'is_active' => 1])->countAllResults(),
            'agent_users' => $this->where(['role' => 'agent', 'is_active' => 1])->countAllResults(),
            'recent_logins' => $this->select('first_name, last_name, email, last_login')
                                   ->where('last_login >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                                   ->where('is_active', 1)
                                   ->orderBy('last_login', 'DESC')
                                   ->findAll(5)
        ];

        return $stats;
    }
}
