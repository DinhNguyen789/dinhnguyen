<?php
require_once __DIR__ . '/BaseController.php';

class AccountController extends BaseController
{
    public function index()
    {
        // Hiển thị trang đăng nhập
        if (isset($_SESSION['user'])) {
            // Nếu đã đăng nhập, chuyển hướng về trang chủ
            header('Location: /webbanhang/Product');
            exit();
        }
        include 'app/views/account/login.php';
    }

    public function login()
{
    // Kiểm tra nếu đã đăng nhập
    if (isset($_SESSION['user'])) {
        $_SESSION['message'] = "Bạn đã đăng nhập với tài khoản " . htmlspecialchars($_SESSION['user']['username']) . ". Vui lòng đăng xuất trước khi đăng nhập tài khoản khác.";
        header('Location: /webbanhang/Product');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = "Vui lòng điền đầy đủ tên đăng nhập và mật khẩu.";
            include 'app/views/account/login.php';
            return;
        }

        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'role' => $user['role']
            ];
            $_SESSION['message'] = "Đăng nhập thành công!";
            header('Location: /webbanhang/Product');
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
            include 'app/views/account/login.php';
        }
    } else {
        include 'app/views/account/login.php';
    }
}
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmpassword = $_POST['confirmpassword'] ?? '';

            // Kiểm tra dữ liệu đầu vào
            $errors = [];
            if (empty($username) || empty($fullname) || empty($password) || empty($confirmpassword)) {
                $errors[] = "Vui lòng điền đầy đủ thông tin.";
            }
            if ($password !== $confirmpassword) {
                $errors[] = "Mật khẩu và xác nhận mật khẩu không khớp.";
            }
            if (strlen($password) < 6) {
                $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
            }

            // Kiểm tra username đã tồn tại chưa
            $query = "SELECT COUNT(*) FROM users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
            }

            if (!empty($errors)) {
                include 'app/views/account/register.php';
                return;
            }

            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng mới
            $query = "INSERT INTO users (username, fullname, password, role) 
                      VALUES (:username, :fullname, :password, 'user')";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            // Chuyển hướng về trang đăng nhập
            header('Location: /webbanhang/account');
            exit();
        } else {
            include 'app/views/account/register.php';
        }
    }

    public function logout()
    {
        // Xóa session người dùng
        unset($_SESSION['user']);
        // Chuyển hướng về trang đăng nhập
        header('Location: /webbanhang/account');
        exit();
    }
}