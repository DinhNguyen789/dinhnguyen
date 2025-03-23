<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    private $db;
    private $categoryModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        $categories = $this->categoryModel->getCategories();
        include __DIR__ . '/../views/category/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->addCategory($name, $description);
            header('Location: /webbanhang/category');
            exit();
        } else {
            include __DIR__ . '/../views/category/add.php';
        }
    }

    public function edit($id = null) {
        if ($id === null) {
            die('Lỗi: Không có ID danh mục để sửa');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->updateCategory($id, $name, $description);
            header('Location: /webbanhang/category');
            exit();
        } else {
            $category = $this->categoryModel->getCategoryById($id);
            include __DIR__ . '/../views/category/edit.php';
        }
    }

    public function delete($id = null) {
        if ($id === null) {
            die('Lỗi: Không có ID danh mục để xóa');
        }

        $this->categoryModel->deleteCategory($id);
        header('Location: /webbanhang/category');
        exit();
    }
}
