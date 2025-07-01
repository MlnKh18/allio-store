<?php
class UserModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllProduct() {
        $sql = "SELECT * FROM product";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM product WHERE id_product = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM product WHERE id_product = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
