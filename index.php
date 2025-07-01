<?php
require_once(__DIR__ . '/configurations/connection.php');
require_once(__DIR__ . '/backend/controllers/authController.php');
require_once(__DIR__ . '/backend/controllers/userController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    if ($page === 'backend/login') {
        // Mengambil data dari body request
        $inputData = json_decode(file_get_contents('php://input'), true);
        $email = isset($inputData['email']) ? trim($inputData['email']) : '';
        $password = isset($inputData['password']) ? $inputData['password'] : '';

        // Validasi input
        if (empty($email) || empty($password)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Email dan password tidak boleh kosong'
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Format email tidak valid'
            ]);
            exit;
        }

        // Menangani login menggunakan AuthController
        $authController = new AuthController();
        $response = $authController->handleLogin($email, $password);
        echo json_encode($response);
    }
    // Menangani Registerasi
    if ($page === 'backend/register') {
        // Mengambil data dari body request
        $inputData = json_decode(file_get_contents('php://input'), true);
        $username = isset($inputData['name_user']) ? $inputData['name_user'] : '';
        $email = isset($inputData['email']) ? $inputData['email'] : '';
        $password = isset($inputData['password']) ? $inputData['password'] : '';

        // Validasi input
        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Username, email, dan password tidak boleh kosong'
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Format email tidak valid'
            ]);
            exit;
        }

        // Menangani registrasi menggunakan AuthController
        $authController = new AuthController();
        $response = $authController->handleRegister($username, $email, $password);
        echo json_encode($response);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page = isset($_GET['page']) ? $_GET['page'] : '';

    if ($page === 'backend/logout') {
        $authController = new AuthController();
        $response = $authController->handleLogout();
        echo json_encode($response);
    } else if ($page === 'backend/getAllUser') {
        $userController = new UserController();
        $response = $userController->handleGetAllUser();
        echo json_encode($response);
    } else {
        echo json_encode([
            'status' => 'error',
            'code' => 404,
            'message' => 'Halaman tidak ditemukan'
        ]);
    }
}
