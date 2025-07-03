<?php
require_once(__DIR__ . '/../services/orderService.php');

class OrderController
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function handleCheckout($userId, $totalAmount, $paymentMethod)
    {
        return $this->orderService->handleCheckout($userId, $totalAmount, $paymentMethod);
    }

    public function handleGetOrderHistory($userId)
    {
        return $this->orderService->handleGetOrderHistory($userId);
    }
    public function handleDeleteOrder($orderId, $userId)
    {
        return $this->orderService->handleDeleteOrderByOrderId($orderId, $userId);
    }
}
