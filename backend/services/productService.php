<?php
require_once(__DIR__ . '/../models/userModel.php');
require_once(__DIR__ . '/../models/adminModel.php');

class ProductService
{
    private $userModel;
    private $adminModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function handleGetAllProducts()
    {
        $result = $this->userModel->getAllProducts();

        // Cek query error
        if ($result === false) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to retrieve products'
            ];
        }

        // Cek jika ada data
        if ($result->num_rows > 0) {
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            return [
                'status' => 'success',
                'code' => 200,
                'data' => $products
            ];
        }

        // Jika data kosong
        return [
            'status' => 'error',
            'code' => 404,
            'message' => 'No products found'
        ];
    }

    public function handleGetProductById($id)
    {
        // Validasi id kosong/null
        if (empty($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid product ID'
            ];
        }

        $result = $this->userModel->getProductById($id);

        // Cek query error
        if ($result === false) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to retrieve product'
            ];
        }

        // Cek jika data ditemukan
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            return [
                'status' => 'success',
                'code' => 200,
                'data' => $product
            ];
        }

        // Jika data tidak ditemukan
        return [
            'status' => 'error',
            'code' => 404,
            'message' => 'Product not found'
        ];
    }
    public function handleAddProduct($name, $price, $description, $image, $categoryId)
    {
        // Validasi input kosong
        if (empty($name) || empty($price) || empty($description) || empty($image) || empty($categoryId)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'All fields are required'
            ];
        }

        // Validasi nama minimal 3 karakter
        if (strlen($name) < 3) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Product name must be at least 3 characters'
            ];
        }

        // Validasi harga angka positif
        if (!is_numeric($price) || $price <= 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Price must be a positive number'
            ];
        }

        // Validasi categoryId angka
        if (!is_numeric($categoryId) || $categoryId <= 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ];
        }

        // Validasi ekstensi gambar (opsional, misal jpg/png/jpeg)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $imageExtension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $allowedExtensions)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid image format. Allowed formats: jpg, jpeg, png, gif'
            ];
        }

        // Panggil model untuk menambahkan produk
        $result = $this->adminModel->addProduct($name, $price, $description, $image, $categoryId);

        if ($result) {
            return [
                'status' => 'success',
                'code' => 201,
                'message' => 'Product added successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to add product'
            ];
        }
    }
    public function handleDeleteProductById($id)
    {
        // Validasi id kosong/null
        if (empty($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid product ID'
            ];
        }

        // Panggil model untuk menghapus produk
        $result = $this->adminModel->deleteProduct($id);

        if ($result) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Product deleted successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to delete product'
            ];
        }
    }
}
