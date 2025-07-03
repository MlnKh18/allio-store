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
    public function updateUser($id, $name = null, $email = null, $roleId = null)
    {
        $fields = [];
        $params = [];
        $types  = "";

        if (!empty($name)) {
            $fields[] = "name_user = ?";
            $params[] = $name;
            $types .= "s";
        }

        if (!empty($email)) {
            $fields[] = "email = ?";
            $params[] = $email;
            $types .= "s";
        }

        if (!empty($roleId)) {
            $fields[] = "role_id = ?";
            $params[] = $roleId;
            $types .= "i";
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $types   .= "i";

        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param($types, ...$params);

        return $stmt->execute();
    }
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
    public function editCategory($id, $name)
    {
        $sql = "UPDATE categories SET name_category = ? WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("si", $name, $id);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return true;
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
        $stmt->bind_param("sdssii", $name, $price, $description, $image, $categoryId, $id);
        return $stmt->execute();
    }

    public function deleteProduct($product_id)
    {
        // Hapus dulu item di cart yang terkait product ini
        $sql = "DELETE FROM cart_items WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        // Baru hapus product-nya
        $query = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
