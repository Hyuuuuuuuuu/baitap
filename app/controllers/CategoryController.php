<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add() {
        include 'app/views/category/add.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                $error = "Tên danh mục không được để trống.";
                include 'app/views/category/add.php';
                return;
            }

            $this->categoryModel->createCategory($name, $description);
            header('Location: /PROJECTBANHANG/Category');
            exit();
        }
    }

    public function edit($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không tìm thấy danh mục.";
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                $error = "Tên danh mục không được để trống.";
                $category = (object)[ 'id' => $id, 'name' => $name, 'description' => $description ];
                include 'app/views/category/edit.php';
                return;
            }

            $this->categoryModel->updateCategory($id, $name, $description);
            header('Location: /PROJECTBANHANG/Category');
            exit();
        }
    }

    public function delete($id) {
        $this->categoryModel->deleteCategory($id);
        header('Location: /PROJECTBANHANG/Category');
        exit();
    }
}
?>
