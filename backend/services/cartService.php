<?php
require_once(__DIR__ . '/../models/userModel.php');

class CartService
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getCartByUserId($userId)
    {
        return $this->userModel->getCartByUserId($userId);
    }

    public function getProductInCartUser($userId, $productId)
    {
        return $this->userModel->getProductInCartUser($userId, $productId);
    }

    public function addProductToCartUser($userId, $productId, $quantity)
    {
        return $this->userModel->addProductToCartUser($userId, $productId, $quantity);
    }

    public function deleteProductFromCartUser($userId, $productId)
    {
        return $this->userModel->deleteProductFromCartUser($userId, $productId);
    }
}
