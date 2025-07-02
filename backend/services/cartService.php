<?php
require_once(__DIR__ . '/../models/userModel.php');
class CartService
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getCartItemsByUserId($userId)
    {
        if (empty($userId)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid user ID'
            ];
        }

        $resultIdUser = $this->userModel->getUserById($userId);
        if ($resultIdUser->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found'
            ];
        }

        $result = $this->userModel->getCartItemsByUserId($userId);
        if ($result === false) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to retrieve cart items'
            ];
        }

        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }

        return [
            'status' => 'success',
            'code' => 200,
            'data' => $cartItems,
            'message' => (count($cartItems) > 0) ? 'Cart items retrieved successfully' : 'Cart is empty'
        ];
    }



    public function addProductToCartUser($userId, $productId, $quantity)
    {
        if (empty($userId) || empty($productId) || empty($quantity)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid input'
            ];
        }
        $resultIdUser = $this->userModel->getUserById($userId);
        if ($resultIdUser->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found'
            ];
        }
        $resultIdProduct = $this->userModel->getProductById($productId);
        if ($resultIdProduct->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Product not found'
            ];
        }
        $result = $this->userModel->addProductToCartUser($userId, $productId, $quantity);
        if ($result) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Product added to cart successfully'
            ];
        }
        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'Failed to add product to cart'
        ];
    }

    public function deleteProductFromCartUser($userId, $productId)
    {
        if (empty($userId) || empty($productId)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid input'
            ];
        }

        $resultIdUser = $this->userModel->getUserById($userId);
        if ($resultIdUser->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found'
            ];
        }

        $resultIdProduct = $this->userModel->getProductById($productId);
        if ($resultIdProduct->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Product not found'
            ];
        }

        $result = $this->userModel->deleteProductFromCartUser($userId, $productId);
        if ($result) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Product removed from cart successfully'
            ];
        }

        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'Failed to remove product from cart'
        ];
    }

    public function clearCart($userId)
    {
        if (empty($userId)) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid user ID'
            ];
        }
        $result = $this->userModel->deleteAllProductFromCartUser($userId);
        if ($result) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Cart cleared successfully'
            ];
        }
        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'Failed to clear cart'
        ];
    }
}
