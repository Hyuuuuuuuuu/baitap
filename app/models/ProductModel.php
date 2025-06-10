<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name FROM " . $this->table_name . " p LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getProductById($id)
    {
        $query = "SELECT p.*, c.name as category_name 
        FROM " . $this->table_name . " p  LEFT JOIN category c ON p.category_id = c.id WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function addProduct($name, $description, $price, $category_id)
    {
        try {
            // Validate dữ liệu
            $errors = [];
            if (empty($name)) {
                $errors['name'] = 'Tên sản phẩm không được để trống';
            }
            if (empty($description)) {
                $errors['description'] = 'Mô tả không được để trống';
            }
            if (!is_numeric($price) || $price < 0) {
                $errors['price'] = 'Giá sản phẩm không hợp lệ';
            }
            if (empty($category_id)) {
                $errors['category_id'] = 'Danh mục không được để trống';
            }

            if (count($errors) > 0) {
                return $errors;
            }

            // Kiểm tra category tồn tại
            $cat_query = "SELECT id FROM category WHERE id = :category_id";
            $cat_stmt = $this->conn->prepare($cat_query);
            $cat_stmt->bindParam(':category_id', $category_id);
            $cat_stmt->execute();
            if (!$cat_stmt->fetch()) {
                $errors['category_id'] = 'Danh mục không tồn tại';
                return $errors;
            }

            // Thêm sản phẩm
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id) 
                     VALUES (:name, :description, :price, :category_id)";
            $stmt = $this->conn->prepare($query);

            // Sanitize dữ liệu
            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));
            $price = (float)$price;
            $category_id = (int)$category_id;

            // Bind các tham số
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);

            // Thực thi query
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }

            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error in addProduct: " . $e->getMessage());
            return false;
        }
    }
    public function updateProduct(
        $id,
        $name,
        $description,
        $price,
        $category_id
    ) {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function deleteProduct($id)
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra sản phẩm có tồn tại
            $check_query = "SELECT id FROM " . $this->table_name . " WHERE id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();
            
            if (!$check_stmt->fetch()) {
                $this->conn->rollBack();
                error_log("Product not found for deletion: " . $id);
                return false;
            }

            // Thực hiện xóa sản phẩm
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                error_log("Product deleted successfully: " . $id);
                return true;
            }

            $this->conn->rollBack();
            error_log("Failed to delete product: " . $id);
            return false;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error deleting product " . $id . ": " . $e->getMessage());
            throw $e;
        }
    }
}