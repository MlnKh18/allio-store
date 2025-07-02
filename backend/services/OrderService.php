<?php
require_once(__DIR__ . '/../models/userModel.php');
require_once(__DIR__ . '/../models/cartService.php');

class OrderService
{
    private $userModel;
    private $cartService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->cartService = new CartService();
    }

    public function handleCheckout($userId, $totalAmount)
    {
        // Create a new order
        $orderId = $this->userModel->createOrder($userId, $totalAmount);
        if (!$orderId) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to create order'
            ];
        }

        // Get cart items for the user
        $cartItems = $this->userModel->getCartItemsByUserId($userId);
        if ($cartItems->num_rows === 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Cart is empty'
            ];
        }

        $orderItems = [];

        // Create order items
        while ($item = $cartItems->fetch_assoc()) {
            $result = $this->userModel->createOrderItem($orderId, $item['product_id'], $item['quantity'], $item['price']);
            if (!$result) {
                return [
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'Failed to create order items'
                ];
            }

            // Simpan item ke array hasil
            $orderItems[] = [
                'product_id'   => (int)$item['product_id'],
                'product_name' => $item['name_product'],
                'quantity'     => (int)$item['quantity'],
                'price'        => (float)$item['price']
            ];
        }

        // Clear the cart
        $this->userModel->deleteProductFromCartUser($userId, null);

        // Return success with order details
        return [
            'status' => 'success',
            'code'   => 201,
            'message' => 'Checkout successful',
            'data'   => [
                'order_id'    => (int)$orderId,
                'total'       => (float)$totalAmount,
                'order_items' => $orderItems
            ]
        ];
    }

}
