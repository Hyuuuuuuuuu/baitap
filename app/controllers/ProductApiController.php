<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductApiController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        
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
            $products = $this->productModel->getProducts();
            echo json_encode(['success' => true, 'data' => $products]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server']);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                echo json_encode(['success' => true, 'data' => $product]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server']);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            error_log("Received data: " . print_r($data, true));
            
            $errors = [];
            if (!isset($data['name']) || empty(trim($data['name']))) {
                $errors['name'] = 'Tên sản phẩm không được để trống';
            }
            if (!isset($data['description']) || empty(trim($data['description']))) {
                $errors['description'] = 'Mô tả không được để trống';
            }
            if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
                $errors['price'] = 'Giá sản phẩm không hợp lệ';
            }
            if (!isset($data['category_id']) || !is_numeric($data['category_id'])) {
                $errors['category_id'] = 'Danh mục không hợp lệ';
            }

            if (!empty($errors)) {
                error_log("Validation errors: " . print_r($errors, true));
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }

            $result = $this->productModel->addProduct(
                trim($data['name']),
                trim($data['description']),
                (float)$data['price'],
                (int)$data['category_id']
            );

            error_log("Add product result: " . print_r($result, true));

            if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $result]);
            } else if ($result === true) {
                http_response_code(201);
                echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Thêm sản phẩm thất bại']);
            }
        } catch (Exception $e) {
            error_log("Exception in store: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $errors = [];
            if (!isset($data['name']) || empty(trim($data['name']))) {
                $errors['name'] = 'Tên sản phẩm không được để trống';
            }
            if (!isset($data['description']) || empty(trim($data['description']))) {
                $errors['description'] = 'Mô tả không được để trống';
            }
            if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
                $errors['price'] = 'Giá sản phẩm không hợp lệ';
            }
            if (!isset($data['category_id']) || !is_numeric($data['category_id'])) {
                $errors['category_id'] = 'Danh mục không hợp lệ';
            }

            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }

            $result = $this->productModel->updateProduct(
                $id,
                trim($data['name']),
                trim($data['description']),
                (float)$data['price'],
                (int)$data['category_id']
            );

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cập nhật sản phẩm thất bại']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server']);
        }
    }

    public function destroy($id)
    {
        try {
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
                return;
            }

            $result = $this->productModel->deleteProduct($id);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Xóa sản phẩm thất bại']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server']);
        }
    }
}
?>