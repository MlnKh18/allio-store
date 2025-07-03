<?php
class UserModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // ===============================================================================================================User 
    public function registerUser($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (name_user, email, password, role_id, created_at) VALUES (?, ?, ?, 2, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();
        return $this->conn->insert_id;
    }
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getAllUser()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ===============================================================================================================Product
    public function getAllProducts()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getProductById($id)
    {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ===============================================================================================================Cart
    public function getCartItemsByUserId($userId)
    {
        $sql = "SELECT p.*, c.quantity FROM products p JOIN cart_items c ON p.product_id = c.product_id WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function addProductToCartUser($userId, $productId, $quantity)
    {
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        return $stmt->execute();
    }
    public function deleteProductFromCartUser($userId, $productId)
    {
        $sql = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }
    public function deleteAllProductFromCartUser($userId)
    {
        $sql = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    // ===============================================================================================================Order
    public function createOrder($userId, $totalAmount)
    {
        $sql = "INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'pending', NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("id", $userId, $totalAmount);

        if ($stmt->execute()) {
            // Kembalikan order_id hasil insert
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    public function createOrderItem($orderId, $productId, $quantity, $price)
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
        return $stmt->execute();
    }
    public function getOrderHistoryWithPaymentByUserId($userId)
    {
        $sql = "SELECT o.*, p.payment_method FROM orders o JOIN payments p ON o.order_id = p.order_id WHERE o.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getOrderItemsByOrderId($orderId)
    {
        $sql = "SELECT oi.*, p.name_product FROM order_items oi 
            JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getOrderByOrderId($orderId)
    {
        $sql = "SELECT * FROM orders WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function deleteOrderHistoryByUserId($id)
    {
        $sql = "DELETE FROM orders WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function deleteOrderByOrderId($orderId)
    {
        // Hapus order items terlebih dahulu
        $sqlItems = "DELETE FROM order_items WHERE order_id = ?";
        $stmtItems = $this->conn->prepare($sqlItems);
        $stmtItems->bind_param("i", $orderId);
        $stmtItems->execute();

        //Hapus pembayaran terkait
        $sqlPayment = "DELETE FROM payments WHERE order_id = ?";
        $stmtPayment = $this->conn->prepare($sqlPayment);
        $stmtPayment->bind_param("i", $orderId);
        $stmtPayment->execute();

        // Hapus order
        $sqlOrder = "DELETE FROM orders WHERE order_id = ?";
        $stmtOrder = $this->conn->prepare($sqlOrder);
        $stmtOrder->bind_param("i", $orderId);
        return $stmtOrder->execute();
    }

    // ===============================================================================================================payment
    public function createPayment($orderId, $paymentMethod)
    {
        $sql = "INSERT INTO payments (order_id, payment_method, payment_status, paid_at) VALUES (?, ?, 'pending', NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $orderId, $paymentMethod);
        return $stmt->execute();
    }
}
