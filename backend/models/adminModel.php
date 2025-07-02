<?php
class AdminModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // =============================================================================================================== User

    public function deleteUserById($id)
    {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // =============================================================================================================== Category
    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getCategoryById($id)
{
    $sql = "SELECT * FROM categories WHERE category_id = ?";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    return $stmt->get_result();
}

    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (name_category) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }
    public function deleteCategoryById($id)
    {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // =============================================================================================================== Product
    public function addProduct($name, $price, $description, $image, $categoryId)
    {
        $sql = "INSERT INTO products (name_product, price, description, image_url, category_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdssi", $name, $price, $description, $image, $categoryId);
        return $stmt->execute();
    }

    public function editProduct($id, $name, $price, $description, $image, $categoryId)
    {
        $sql = "UPDATE products SET name_product = ?, price = ?, description = ?, image_url = ?, category_id = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdssi", $name, $price, $description, $image, $categoryId, $id);
        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
