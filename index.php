<?php
require_once(__DIR__ . '/configurations/connection.php');
require_once(__DIR__ . '/backend/controllers/authController.php');
require_once(__DIR__ . '/backend/controllers/userController.php');
require_once(__DIR__ . '/backend/controllers/productController.php');
require_once(__DIR__ . '/backend/controllers/categoryController.php');

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
    if ($page === 'backend/getUserByEmail') {
        // Mengambil data dari body request
        $inputData = json_decode(file_get_contents('php://input'), true);
        $email = isset($inputData['email']) ? trim($inputData['email']) : '';

        // Validasi input
        if (empty($email)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Email tidak boleh kosong'
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

        // Menangani pengambilan user berdasarkan email
        $userController = new UserController();
        $response = $userController->handleGetUserByEmail($email);
        echo json_encode($response);
    }
    if ($page === 'backend/addCategory') {
        // Mengambil data dari body request
        $inputData = json_decode(file_get_contents('php://input'), true);
        $name = isset($inputData['name_category']) ? trim($inputData['name_category']) : '';

        // Validasi input
        if (empty($name)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Name cannot be empty'
            ]);
            exit;
        }

        // Menangani penambahan kategori menggunakan CategoryController
        $categoryController = new CategoryController();
        $response = $categoryController->handleAddCategory($name);
        echo json_encode($response);
    }
    if ($page === 'backend/getCategoryById') {
        // Mengambil data dari body request
        $inputData = json_decode(file_get_contents('php://input'), true);
        $id = isset($inputData['id']) ? intval($inputData['id']) : 0;

        // Validasi input
        if ($id <= 0) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ]);
            exit;
        }

        // Menangani pengambilan kategori berdasarkan ID
        $categoryController = new CategoryController();
        $response = $categoryController->handleGetCategoryById($id);
        echo json_encode($response);
    }
    if ($page === 'backend/addProduct') {
        // Mengambil data dari body request
        $inputData = json_decode(file_get_contents('php://input'), true);
        $name = isset($inputData['name']) ? trim($inputData['name']) : '';
        $price = isset($inputData['price']) ? $inputData['price'] : '';
        $description = isset($inputData['description']) ? $inputData['description'] : '';
        $image = isset($inputData['image']) ? $inputData['image'] : '';
        $categoryId = isset($inputData['categoryId']) ? $inputData['categoryId'] : '';

        // Validasi input
        if (empty($name) || empty($price) || empty($description) || empty($image) || empty($categoryId)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'All fields are required'
            ]);
            exit;
        }

        // Menangani penambahan produk menggunakan ProductController
        $productController = new ProductController();
        $response = $productController->handleAddProduct($name, $price, $description, $image, $categoryId);
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
    } else if ($page === 'backend/getAllCategory') {
        $categoryController = new CategoryController();
        $response = $categoryController->handleGetAllCategories();
        echo json_encode($response);
    } else {
        echo json_encode([
            'status' => 'error',
            'code' => 404,
            'message' => 'Halaman tidak ditemukan'
        ]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $page = isset($_GET['page']) ? $_GET['page'] : '';

    if ($page === 'backend/deleteUserById') {
        $inputData = json_decode(file_get_contents('php://input'), true);
        $id = isset($inputData['user_id']) ? intval($inputData['user_id']) : 0;

        // Validasi input
        if ($id <= 0) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid user ID'
            ]);
            exit;
        }

        // Menangani penghapusan pengguna berdasarkan ID
        $userController = new UserController();
        $response = $userController->handleDeleteUserById($id);
        echo json_encode($response);
    } else if ($page === 'backend/deleteCategoryById') {
        $inputData = json_decode(file_get_contents('php://input'), true);
        $id = isset($inputData['id']) ? intval($inputData['id']) : 0;

        // Validasi input
        if ($id <= 0) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ]);
            exit;
        }

        // Menangani penghapusan kategori berdasarkan ID
        $categoryController = new CategoryController();
        $response = $categoryController->handleDeleteCategory($id);
        echo json_encode($response);
    }
}
