<?php
require_once(__DIR__ . '/../models/userModel.php');

class OrderService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function handleCheckout($userId, $totalAmount, $paymentMethod)
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

        $paymentResult = $this->userModel->createPayment($orderId, $paymentMethod);
        if (!$paymentResult) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to create payment'
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
    public function handleGetOrderHistory($userId)
    {
        // Get order history for the user
        $orderHistory = $this->userModel->getOrderHistoryWithPaymentByUserId($userId);
        if ($orderHistory->num_rows === 0) {
            return [
                'status' => 'error',
                'code'   => 404,
                'message' => 'No order history found'
            ];
        }

        $orders = [];
        while ($order = $orderHistory->fetch_assoc()) {
            // Dapatkan order items untuk order_id ini
            $orderItemsResult = $this->userModel->getOrderItemsByOrderId($order['order_id']);
            $orderItems = [];

            if ($orderItemsResult->num_rows > 0) {
                while ($item = $orderItemsResult->fetch_assoc()) {
                    $orderItems[] = [
                        'product_id'   => (int)$item['product_id'],
                        'product_name' => $item['name_product'],
                        'quantity'     => (int)$item['quantity'],
                        'price'        => (float)$item['price']
                    ];
                }
            }

            // Gabungkan ke dalam order
            $orders[] = [
                'order_id'       => (int)$order['order_id'],
                'total_amount'   => (float)$order['total_amount'],
                'status'         => $order['status'],
                'created_at'     => $order['created_at'],
                'payment_method' => $order['payment_method'],
                'order_items'    => $orderItems
            ];
        }

        return [
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Order history retrieved successfully',
            'data'    => $orders
        ];
    }
    public function handleDeleteOrderByOrderId($orderId, $userId)
    {
        // Ambil detail order berdasarkan order_id
        $orderResult = $this->userModel->getOrderByOrderId($orderId);
        if (!$orderResult || $orderResult->num_rows === 0) {
            return [
                'status' => 'error',
                'code'   => 404,
                'message' => 'Order not found'
            ];
        }

        $order = $orderResult->fetch_assoc();

        if ((int)$order['user_id'] !== (int)$userId) {
            return [
                'status' => 'error',
                'code'   => 403,
                'message' => 'Unauthorized to delete this order'
            ];
        }

        // Hapus order berdasarkan order_id
        $result = $this->userModel->deleteOrderByOrderId($orderId);
        if (!$result) {
            return [
                'status' => 'error',
                'code'   => 500,
                'message' => 'Failed to delete order'
            ];
        }

        return [
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Order deleted successfully'
        ];
    }
}
