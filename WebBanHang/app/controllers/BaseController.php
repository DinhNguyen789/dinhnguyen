<?php
// Xóa dòng session_start() vì đã được gọi trong BaseController.php
require_once 'app/controllers/AccountController.php';
require_once 'app/controllers/ProductController.php';
require_once 'app/controllers/CategoryController.php';

$request = $_SERVER['REQUEST_URI'];

class BaseController
{
    protected $db;

    public function __construct()
    {
         // Khởi tạo session
        $database = new Database();
        $this->db = $database->getConnection();
    }
}