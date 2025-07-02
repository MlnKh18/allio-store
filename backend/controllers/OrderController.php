<?php
require_once(__DIR__ . '/../services/OrderService.php');

class OrderController
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function handleCheckout($userId, $totalAmount)
    {
        return $this->orderService->handleCheckout($userId, $totalAmount);
    }

}
