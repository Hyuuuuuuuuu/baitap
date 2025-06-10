<?php 
class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCategories()
    {
        try {
            $query = "SELECT id, name, description FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error in getCategories: " . $e->getMessage());
            throw $e;
        }
    }

    public function getCategoryById($id)
    {
        try {
            $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error in getCategoryById: " . $e->getMessage());
            throw $e;
        }
    }

    public function createCategory($name, $description)
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra tên danh mục đã tồn tại chưa
            $check_query = "SELECT id FROM " . $this->table_name . " WHERE name = :name LIMIT 1";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(":name", $name);
            $check_stmt->execute();

            if ($check_stmt->fetch()) {
                $this->conn->rollBack();
                error_log("Category name already exists: " . $name);
                return false;
            }

            $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize và bind dữ liệu
            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));
            
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);

            if ($stmt->execute()) {
                $this->conn->commit();
                error_log("Category created successfully: " . $name);
                return true;
            }

            $this->conn->rollBack();
            error_log("Failed to create category: " . $name);
            return false;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error in createCategory: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateCategory($id, $name, $description)
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra tên danh mục đã tồn tại chưa (trừ danh mục hiện tại)
            $check_query = "SELECT id FROM " . $this->table_name . " WHERE name = :name AND id != :id LIMIT 1";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(":name", $name);
            $check_stmt->bindParam(":id", $id);
            $check_stmt->execute();

            if ($check_stmt->fetch()) {
                $this->conn->rollBack();
                error_log("Category name already exists: " . $name);
                return false;
            }

            $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize và bind dữ liệu
            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));
            
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                $this->conn->commit();
                error_log("Category updated successfully: ID=" . $id);
                return true;
            }

            $this->conn->rollBack();
            error_log("Failed to update category: ID=" . $id);
            return false;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error in updateCategory: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCategory($id)
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra xem danh mục có tồn tại không
            $check_query = "SELECT id FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(":id", $id);
            $check_stmt->execute();

            if (!$check_stmt->fetch()) {
                $this->conn->rollBack();
                error_log("Category not found for deletion: ID=" . $id);
                return false;
            }

            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                $this->conn->commit();
                error_log("Category deleted successfully: ID=" . $id);
                return true;
            }

            $this->conn->rollBack();
            error_log("Failed to delete category: ID=" . $id);
            return false;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error in deleteCategory: " . $e->getMessage());
            throw $e;
        }
    }
}
?>
