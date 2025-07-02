<?php
require_once(__DIR__ . '/../services/categoryService.php');

class CategoryController
{
    private $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function handleGetAllCategories()
    {
        return $this->categoryService->handleGetAllCategories();
    }

    public function handleGetCategoryById($id)
    {
        return $this->categoryService->handleGetCategoryById($id);
    }

    public function handleAddCategory($name)
    {
        return $this->categoryService->handleAddCategory($name);
    }

    // public function handleUpdateCategory($id, $data)
    // {
    //     return $this->categoryService->handleUpdateCategory($id, $data);
    // }

    public function handleDeleteCategory($id)
    {
        return $this->categoryService->handleDeleteCategoryById($id);
    }
}
