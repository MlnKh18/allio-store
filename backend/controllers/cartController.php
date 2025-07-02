<?php
require_once(__DIR__ . '/../services/cartService.php');

class CartController
{
    private $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function getCartItems($userId)
    {
        return $this->cartService->getCartItemsByUserId($userId);
    }

    public function addProductToCart($userId, $productId, $quantity)
    {
        return $this->cartService->addProductToCartUser($userId, $productId, $quantity);
    }

    public function deleteProductFromCart($userId, $productId)
    {
        return $this->cartService->deleteProductFromCartUser($userId, $productId);
    }
}
