<?php
class UserModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

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

    // public function getAllProduct()
    // {
    //     $sql = "SELECT * FROM product";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute();
    //     return $stmt->get_result();
    // }

    // public function getProductById($id)
    // {
    //     $sql = "SELECT * FROM product WHERE id_product = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     return $stmt->get_result();
    // }

    // public function deleteProduct($id)
    // {
    //     $sql = "DELETE FROM product WHERE id_product = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    // }
}
