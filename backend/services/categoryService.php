<?php
require_once(__DIR__ . '/../models/adminModel.php');


class CategoryService
{
    private $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function handleGetAllCategories()
    {
        $result = $this->adminModel->getAllCategories();
        if ($result->num_rows > 0) {
            return [
                'status' => 'ok',
                'message' => 'Categories retrieved successfully',
                'categories' => $result->fetch_all(MYSQLI_ASSOC)
            ];
        }
        if ($result->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'No categories found'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while retrieving categories'
            ];
        }
    }

    public function handleGetCategoryById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ];
        }
        $result = $this->adminModel->getCategoryById($id);
        if ($result && is_object($result) && $result->num_rows > 0) {
            return [
                'status' => 'ok',
                'message' => 'Category retrieved successfully',
                'category' => $result->fetch_assoc()
            ];
        }
        if ($result && is_object($result) && $result->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Category not found'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while retrieving the category'
            ];
        }
    }

    public function handleAddCategory($name)
    {
        if (empty($name)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Category name cannot be empty'
            ];
        }

        // Cek apakah ada kategori dengan nama yang sama
        $existingCategories = $this->adminModel->getAllCategories();
        if ($existingCategories && $existingCategories->num_rows > 0) {
            while ($row = $existingCategories->fetch_assoc()) {
                if (strcasecmp(trim($row['name_category']), trim($name)) === 0) {
                    return [
                        'status' => 'error',
                        'code' => 409,
                        'message' => 'Category with the same name already exists'
                    ];
                }
            }
        }

        $result = $this->adminModel->addCategory($name);
        if ($result) {
            return [
                'status' => 'ok',
                'message' => 'Category added successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while adding the category'
            ];
        }
    }

    public function handleUpdateCategory($id, $data)
    {
        // Validasi ID
        if (empty($id) || !is_numeric($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ];
        }

        // Validasi data input
        if (!is_array($data) || !array_key_exists('name_category', $data) || is_null($data['name_category'])) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid input data'
            ];
        }

        $name = trim($data['name_category']);
        if (empty($name)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Category name cannot be empty'
            ];
        }

        // Cek apakah kategori dengan ID tersebut ada
        $existingCategory = $this->adminModel->getCategoryById($id);
        if (!$existingCategory || !is_object($existingCategory) || $existingCategory->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Category not found'
            ];
        }

        // Cek apakah nama kategori sudah digunakan oleh kategori lain
        $allCategories = $this->adminModel->getAllCategories();
        if ($allCategories && $allCategories->num_rows > 0) {
            while ($row = $allCategories->fetch_assoc()) {
                // Pastikan id_category-nya memang ada di row
                if (!isset($row['id_category'])) {
                    continue; // skip kalau nggak ada id_category
                }

                // Bandingkan nama kategori (case insensitive) dan pastikan bukan dirinya sendiri
                if (
                    strcasecmp(trim($row['name_category']), $name) === 0 &&
                    intval($row['id_category']) !== intval($id)
                ) {
                    return [
                        'status' => 'error',
                        'code'   => 409,
                        'message' => 'Category name already exists'
                    ];
                }
            }
        }


        // Update kategori
        $result = $this->adminModel->editCategory($id, $name);
        if ($result) {
            return [
                'status' => 'ok',
                'message' => 'Category updated successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while updating the category'
            ];
        }
    }

    public function handleDeleteCategoryById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid category ID'
            ];
        }

        $result = $this->adminModel->deleteCategoryById($id);
        if ($result) {
            return [
                'status' => 'ok',
                'message' => 'Category deleted successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while deleting the category'
            ];
        }
    }
}
