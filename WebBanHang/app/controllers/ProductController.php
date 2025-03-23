<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Kiểm tra quyền admin
    private function requireAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['message'] = "Bạn không có quyền truy cập chức năng này.";
            header('Location: /webbanhang/Product');
            exit();
        }
    }

    public function index()
    { 
        $this->list();
    }

    public function list($category = null) {
        if ($category) {
            $category = trim($category);
            $category = strtolower($category);
            $products = $this->productModel->getProductsByCategory($category);
            if (empty($products)) {
                echo "<p class='text-danger'>Không tìm thấy sản phẩm cho danh mục: $category</p>";
            }
        } else {
            $products = $this->productModel->getAllProducts();
        }
        include __DIR__ . '/../views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        $this->requireAdmin(); // Kiểm tra quyền admin
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        $this->requireAdmin(); // Kiểm tra quyền admin
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }

    public function edit($id)
    {
        $this->requireAdmin(); // Kiểm tra quyền admin
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        $this->requireAdmin(); // Kiểm tra quyền admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        $this->requireAdmin(); // Kiểm tra quyền admin
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function addToCart($id)
{
    // Kiểm tra nếu chưa đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['message'] = "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.";
        header('Location: /webbanhang/account');
        exit();
    }

    // Kiểm tra nếu là admin
    if ($_SESSION['user']['role'] === 'admin') {
        $_SESSION['message'] = "Tài khoản admin không được phép sử dụng chức năng giỏ hàng.";
        header('Location: /webbanhang/Product');
        exit();
    }

    $product = $this->productModel->getProductById($id);
    if (!$product) {
        echo "Không tìm thấy sản phẩm.";
        return;
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->image
        ];
    }
    header('Location: /webbanhang/Product/cart');
}

public function cart()
{
    // Kiểm tra nếu chưa đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['message'] = "Vui lòng đăng nhập để xem giỏ hàng.";
        header('Location: /webbanhang/account');
        exit();
    }

    // Kiểm tra nếu là admin
    if ($_SESSION['user']['role'] === 'admin') {
        $_SESSION['message'] = "Tài khoản admin không được phép sử dụng chức năng giỏ hàng.";
        header('Location: /webbanhang/Product');
        exit();
    }

    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    include 'app/views/Product/cart.php';
}

public function checkout()
{
    // Kiểm tra nếu chưa đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['message'] = "Vui lòng đăng nhập để đặt hàng.";
        header('Location: /webbanhang/account');
        exit();
    }

    // Kiểm tra nếu là admin
    if ($_SESSION['user']['role'] === 'admin') {
        $_SESSION['message'] = "Tài khoản admin không được phép sử dụng chức năng đặt hàng.";
        header('Location: /webbanhang/Product');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedItems = $_POST['selected_items'] ?? [];
        $quantities = $_POST['quantities'] ?? [];

        if (empty($selectedItems)) {
            $error = "Vui lòng chọn ít nhất một sản phẩm để thanh toán!";
            $cart = $_SESSION['cart'] ?? [];
            include __DIR__ . '/../views/product/cart.php';
            return;
        }

        $cart = $_SESSION['cart'] ?? [];
        $selectedCart = [];

        foreach ($selectedItems as $index) {
            if (isset($cart[$index])) {
                $cart[$index]['quantity'] = $quantities[$index] ?? $cart[$index]['quantity'];
                $selectedCart[$index] = $cart[$index];
            }
        }

        $_SESSION['selected_cart'] = $selectedCart;

        include __DIR__ . '/../views/product/checkout.php';
    } else {
        $cart = $_SESSION['cart'] ?? [];
        include __DIR__ . '/../views/product/cart.php';
    }
}

    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: /webbanhang/Product/cart");
        exit;
    }

    public function clearCart() {
        unset($_SESSION['cart']);
        header("Location: /webbanhang/Product/cart");
        exit;
    }

    public function processCheckout()
{
    // Kiểm tra nếu chưa đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['message'] = "Vui lòng đăng nhập để đặt hàng.";
        header('Location: /webbanhang/account');
        exit();
    }

    // Kiểm tra nếu là admin
    if ($_SESSION['user']['role'] === 'admin') {
        $_SESSION['message'] = "Tài khoản admin không được phép sử dụng chức năng đặt hàng.";
        header('Location: /webbanhang/Product');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'] ?? 'Anh';
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $district = $_POST['district'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $address = $_POST['address'] ?? '';
        $note = $_POST['note'] ?? '';

        if (empty($name) || empty($phone) || empty($district) || empty($ward) || empty($address)) {
            $error = "Vui lòng điền đầy đủ thông tin họ tên, số điện thoại và địa chỉ.";
            $cart = $_SESSION['cart'] ?? [];
            include 'app/views/product/cart.php';
            return;
        }

        if (!isset($_SESSION['selected_cart']) || empty($_SESSION['selected_cart'])) {
            $error = "Giỏ hàng trống hoặc không có sản phẩm được chọn.";
            $cart = $_SESSION['cart'] ?? [];
            include 'app/views/product/cart.php';
            return;
        }

        $cart = $_SESSION['selected_cart'];
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $discount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
        $total_price = $subtotal - $discount;
        $total_price = number_format($total_price, 2, '.', '');
        $discount = number_format($discount, 2, '.', '');

        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO orders (title, name, phone, district, ward, address, note, total_price, discount, created_at) 
                      VALUES (:title, :name, :phone, :district, :ward, :address, :note, :total_price, :discount, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':district', $district);
            $stmt->bindParam(':ward', $ward);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
            $stmt->bindParam(':discount', $discount, PDO::PARAM_STR);
            $stmt->execute();
            $order_id = $this->db->lastInsertId();

            foreach ($cart as $product_id => $item) {
                $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':price', $item['price']);
                $stmt->execute();
            }

            $selectedItems = array_keys($cart);
            $fullCart = $_SESSION['cart'] ?? [];
            foreach ($selectedItems as $index) {
                unset($fullCart[$index]);
            }
            $_SESSION['cart'] = array_values($fullCart);
            unset($_SESSION['selected_cart']);
            unset($_SESSION['discount']);

            $this->db->commit();

            $orderDetails = [
                'order_id' => $order_id,
                'title' => $title,
                'name' => $name,
                'phone' => $phone,
                'district' => $district,
                'ward' => $ward,
                'address' => $address,
                'note' => $note,
                'total_price' => $total_price,
                'discount' => $discount,
                'created_at' => date('Y-m-d H:i:s'),
                'items' => $cart
            ];

            include 'app/views/product/orderConfirmation.php';
        } catch (Exception $e) {
            $this->db->rollBack();
            $error = "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            $cart = $_SESSION['cart'] ?? [];
            include 'app/views/product/cart.php';
        }
    } else {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/cart.php';
    }
}

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
?>

