<?php
require_once(__DIR__ . '/../models/userModel.php');

class authService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new userModel();
    }

    public function handleLogin($email, $password)
    {
        if (empty($email) || empty($password)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Email dan password wajib diisi'
            ];
        }

        $result = $this->userModel->getUserByEmail($email);
        if ($result->num_rows == 0) {
            return [
                'status' => 'error',
                'code' => 401,
                'message' => 'Email atau password salah'
            ];
        }

        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['password'])) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Email atau password salah'
            ];
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name_user'] = $user['name_user'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];

        return [
            'status' => 'ok',
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user['user_id'],
                'name_user' => $user['name_user'],
                'email' => $user['email'],
                'role' => $user['role_id']
            ]
        ];
    }


    public function handleRegister($username, $email, $password)
    {
        $result = $this->userModel->getUserByEmail($email);

        if ($result->num_rows > 0) {
            return [
                'status' => 'error',
                'code' => 409,
                'message' => 'Email already exists'
            ];
        }

        $newUserId = $this->userModel->registerUser($username, $email, $password);
        if (!$newUserId) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to register user'
            ];
        }

        $newUser = [
            'id' => $newUserId,
            'name_user' => $username,
            'email' => $email
        ];

        return [
            'status' => 'ok',
            'message' => 'Registration successful',
            'user' => $newUser
        ];
    }

    public function handleLogout()
    {
        session_start();
        session_destroy();
        return [
            'status' => 'ok',
            'message' => 'Logout successful'
        ];
    }
}
