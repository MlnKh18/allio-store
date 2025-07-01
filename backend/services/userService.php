<?php
require_once(__DIR__ . '/../models/userModel.php');
class UserService
{
    private $userModel;
    private $adminModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->adminModel = new AdminModel();
    }
    public function handleGetAllUser()
    {
        $result = $this->userModel->getAllUser();
        if ($result->num_rows > 0) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = [
                    'id' => $row['user_id'],
                    'name_user' => $row['name_user'],
                    'email' => $row['email'],
                    'role' => $row['role_id']
                ];
            }
            return [
                'status' => 'ok',
                'message' => 'Users retrieved successfully',
                'users' => $users
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'No users found'
            ];
        }
    }
    public function handleGetUserByEmail($email)
    {
        $result = $this->userModel->getUserByEmail($email);

        if ($result->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found'
            ];
        }

        $user = $result->fetch_assoc();

        return [
            'status' => 'ok',
            'message' => 'User retrieved successfully',
            'user' => [
                'id' => $user['user_id'],
                'name_user' => $user['name_user'],
                'email' => $user['email'],
                'role' => $user['role_id']
            ]
        ];
    }
    public function handleGetUserById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid user ID'
            ];
        }

        $result = $this->userModel->getUserById($id);

        if ($result->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found'
            ];
        }

        $user = $result->fetch_assoc();

        return [
            'status' => 'ok',
            'message' => 'User retrieved successfully',
            'user' => [
                'id' => $user['user_id'],
                'name_user' => $user['name_user'],
                'email' => $user['email'],
                'role' => $user['role_id']
            ]
        ];
    }
    public function handleDeleteUserById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid user ID'
            ];
        }
        $existingUser = $this->userModel->getUserById($id);
        if ($existingUser->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found'
            ];
        }

        $result = $this->adminModel->deleteUserById($id);

        if ($result) {
            return [
                'status' => 'ok',
                'message' => 'User deleted successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to delete user'
            ];
        }
    }
}
