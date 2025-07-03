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
        $this->adminModel = new AdminModel();
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
        if (empty($name) || empty($price) || empty($description) || empty($image) || empty($categoryId)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'All fields are required'
            ];
        }

        if (strlen($name) < 3) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Product name must be at least 3 characters'
            ];
        }

        if (!is_numeric($price) || $price <= 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Price must be a positive number'
            ];
        }

        if (!is_numeric($categoryId) || $categoryId <= 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ];
        }

        // Validasi apakah image adalah URL yang valid
        if (!filter_var($image, FILTER_VALIDATE_URL)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Image must be a valid URL'
            ];
        }

        // Optional: Validasi ekstensi gambar dari URL (jika tetap mau)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $imageExtension = strtolower(pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $allowedExtensions)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Image URL must point to a valid image file (jpg, jpeg, png, gif)'
            ];
        }

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

    public function handleEditProduct($id, $name, $price, $description, $image, $categoryId)
    {
        if (empty($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid product ID'
            ];
        }
        $executeId = $this->userModel->getProductById($id);
        if ($executeId->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Product not found'
            ];
        }
        $executeCategory = $this->adminModel->getCategoryById($categoryId);
        if ($executeCategory->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Category not found'
            ];
        }

        if (empty($name) || empty($price) || empty($description) || empty($image) || empty($categoryId)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'All fields are required'
            ];
        }

        if (strlen($name) < 3) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Product name must be at least 3 characters'
            ];
        }

        if (!is_numeric($price) || $price <= 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Price must be a positive number'
            ];
        }

        if (!is_numeric($categoryId) || $categoryId <= 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ];
        }

        // Validasi apakah image adalah URL yang valid
        if (!filter_var($image, FILTER_VALIDATE_URL)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Image must be a valid URL'
            ];
        }

        // Optional: Validasi ekstensi gambar dari URL
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $imageExtension = strtolower(pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $allowedExtensions)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Image URL must point to a valid image file (jpg, jpeg, png, gif)'
            ];
        }

        $result = $this->adminModel->editProduct($id, $name, $price, $description, $image, $categoryId);

        if ($result) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Product edited successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to edit product'
            ];
        }
    }


    public function handleDeleteProductById($product_id)
    {
        // Validasi id kosong/null
        if (empty($product_id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid product ID'
            ];
        }

        // Panggil model untuk menghapus produk
        $result = $this->adminModel->deleteProduct($product_id);

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
