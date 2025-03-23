<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DinhNguyenApple</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/webbanhang/public/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="/webbanhang/Product">DinhNguyenApple</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Danh Mục
                    </a>
                    <div class="dropdown-menu" id="categoryDropdownMenu" aria-labelledby="categoryDropdown">
                        <a class="dropdown-item" href="/webbanhang/Product/list/laptop">Laptop</a>
                        <a class="dropdown-item" href="/webbanhang/Product/list/audiodevice">AudioDevice</a>
                        <a class="dropdown-item" href="/webbanhang/Product/list/phone">Phone</a>
                        <a class="dropdown-item" href="/webbanhang/Product/list/accessory">Accessory</a>
                        <a class="dropdown-item" href="/webbanhang/Product/list/tablet">Tablet</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 mr-3">
                <input class="form-control mr-sm-2 search-bar" type="search" placeholder="Tìm kiếm..." aria-label="Search">
                <button class="btn btn-warning my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <ul class="navbar-nav">
                <!-- Chỉ hiển thị biểu tượng giỏ hàng cho user hoặc chưa đăng nhập -->
                <?php if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/cart">
                            <i class="fas fa-shopping-cart mr-1"></i> Giỏ hàng
                            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                                <span class="badge badge-warning"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user mr-1"></i> Xin chào, <?php echo htmlspecialchars($_SESSION['user']['fullname']); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/webbanhang/account/logout">
                                <i class="fas fa-sign-out-alt mr-1"></i> Đăng xuất
                            </a>
                        </div>
                    <?php else: ?>
                        <a class="nav-link" href="/webbanhang/account" data-toggle="none">
                            <i class="fas fa-sign-in-alt mr-1"></i> Đăng nhập
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="/webbanhang/public/js/script.js"></script>