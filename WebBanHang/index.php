<?php
session_start();

// Require các controller
require_once 'app/controllers/AccountController.php';
require_once 'app/controllers/ProductController.php';
require_once 'app/controllers/CategoryController.php';

// Lấy đường dẫn từ URL
$request = $_SERVER['REQUEST_URI'];

// Xử lý điều hướng
$controller = null;
$action = null;
$params = [];

// Loại bỏ phần "/webbanhang" khỏi URL
$basePath = '/webbanhang';
$uri = str_replace($basePath, '', $request);
$uri = trim($uri, '/');

// Chia nhỏ URL thành các phần
$segments = explode('/', $uri);

// Xác định controller, action và params
if (empty($segments[0])) {
    $controller = 'ProductController';
    $action = 'index';
} else {
    switch (strtolower($segments[0])) {
        case 'account':
            $controller = 'AccountController';
            $action = isset($segments[1]) ? $segments[1] : 'index';
            break;
        case 'product':
            $controller = 'ProductController';
            $action = isset($segments[1]) ? $segments[1] : 'index';
            $params = array_slice($segments, 2);
            break;
        case 'category':
            $controller = 'CategoryController';
            $action = isset($segments[1]) ? $segments[1] : 'index';
            $params = array_slice($segments, 2);
            break;
        default:
            $controller = 'ProductController';
            $action = 'index';
            break;
    }
}

// Khởi tạo controller và gọi action
if ($controller && $action) {
    $controllerInstance = new $controller();
    if (method_exists($controllerInstance, $action)) {
        call_user_func_array([$controllerInstance, $action], $params);
    } else {
        echo "Action không tồn tại!";
    }
} else {
    echo "Không tìm thấy controller!";
}