<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        
        // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }

    public function index()
    {
        try {
            $categories = $this->categoryModel->getCategories();
            echo json_encode([
                'success' => true,
                'data' => $categories
            ]);
        } catch (Exception $e) {
            error_log("Error in CategoryApiController::index: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách danh mục'
            ]);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryModel->getCategoryById($id);
            if ($category) {
                echo json_encode([
                    'success' => true,
                    'data' => $category
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error in CategoryApiController::show: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin danh mục'
            ]);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            error_log("Received data for category creation: " . print_r($data, true));

            // Validate dữ liệu
            $errors = [];
            if (!isset($data['name']) || empty(trim($data['name']))) {
                $errors['name'] = 'Tên danh mục không được để trống';
            }
            if (!isset($data['description'])) {
                $errors['description'] = 'Mô tả không được để trống';
            }

            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'errors' => $errors
                ]);
                return;
            }

            // Sanitize dữ liệu
            $name = htmlspecialchars(strip_tags(trim($data['name'])));
            $description = htmlspecialchars(strip_tags(trim($data['description'])));

            if ($this->categoryModel->createCategory($name, $description)) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Thêm danh mục thành công'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Thêm danh mục thất bại'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error in CategoryApiController::store: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi thêm danh mục'
            ]);
        }
    }

    public function update($id)
    {
        try {
            // Kiểm tra danh mục tồn tại
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ]);
                return;
            }

            $data = json_decode(file_get_contents("php://input"), true);
            error_log("Received data for category update: " . print_r($data, true));

            // Validate dữ liệu
            $errors = [];
            if (!isset($data['name']) || empty(trim($data['name']))) {
                $errors['name'] = 'Tên danh mục không được để trống';
            }
            if (!isset($data['description'])) {
                $errors['description'] = 'Mô tả không được để trống';
            }

            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'errors' => $errors
                ]);
                return;
            }

            // Sanitize dữ liệu
            $name = htmlspecialchars(strip_tags(trim($data['name'])));
            $description = htmlspecialchars(strip_tags(trim($data['description'])));

            if ($this->categoryModel->updateCategory($id, $name, $description)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cập nhật danh mục thành công'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Cập nhật danh mục thất bại'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error in CategoryApiController::update: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi cập nhật danh mục'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            // Kiểm tra danh mục tồn tại
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ]);
                return;
            }

            // Kiểm tra xem danh mục có sản phẩm không
            $query = "SELECT COUNT(*) as count FROM product WHERE category_id = :category_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category_id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result->count > 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể xóa danh mục này vì đang có sản phẩm thuộc danh mục'
                ]);
                return;
            }

            if ($this->categoryModel->deleteCategory($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Xóa danh mục thành công'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Xóa danh mục thất bại'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error in CategoryApiController::destroy: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi xóa danh mục'
            ]);
        }
    }
}
?>