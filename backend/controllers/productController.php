<?php
require_once(__DIR__ . '/../models/userModel.php');
require_once(__DIR__ . '/../services/productService.php');
require_once(__DIR__ . '/../models/adminModel.php');

class ProductController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function handleGetAllProducts()
    {
        return $this->productService->handleGetAllProducts();
    }

    public function handleGetProductById($id)
    {
        return $this->productService->handleGetProductById($id);
    }

    public function handleAddProduct($name, $price, $description, $image, $categoryId)
    {
        return $this->productService->handleAddProduct($name, $price, $description, $image, $categoryId);
    }

    // public function handleUpdateProduct($id, $data)
    // {
    //     return $this->productService->handleUpdateProduct($id, $data);
    // }

    public function handleDeleteProduct($id)
    {
        return $this->productService->handleDeleteProductById($id);
    }
}
?>