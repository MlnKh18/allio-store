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
        $sql = "SELECT * FROM products WHERE id_product = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ===============================================================================================================Cart
    public function getCartByUserId($userId)
    {
        $sql = "SELECT p.*, c.quantity FROM product p JOIN cart c ON p.id_product = c.product_id WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getProductInCartUser($userId, $productId)
    {
        $sql = "SELECT p.*, c.quantity FROM product p JOIN cart c ON p.id_product = c.product_id WHERE c.user_id = ? AND c.product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function addProductToCartUser($userId, $productId, $quantity)
    {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        return $stmt->execute();
    }
    public function deleteProductFromCartUser($userId, $productId)
    {
        $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }
}
