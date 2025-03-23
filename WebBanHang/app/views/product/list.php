<?php
ob_clean();
include 'app/views/shares/header.php';
?>

<div class="container mt-5">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Nút "Thêm sản phẩm" - Chỉ hiển thị cho admin -->
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <div class="mb-4">
            <a href="/webbanhang/Product/add" class="btn btn-success">Thêm sản phẩm</a>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($products)): ?>
            <?php $index = 0; ?>
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card" style="--index: <?php echo $index; ?>">
                    <?php if ($product->image): ?>
                    <img src="/webbanhang/<?php echo $product->image; ?>" class="card-img-top" alt="Hình sản phẩm">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <p class="card-text"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Giá:</strong> <?php echo number_format($product->price, 0, ',', '.'); ?> VND</p>
                        <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product->display_name ?? $product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>
                        <!-- Chỉ hiển thị nút "Sửa" và "Xóa" cho admin -->
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Sửa</a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                        <?php endif; ?>
                        <!-- Chỉ hiển thị nút "Thêm vào giỏ hàng" cho user hoặc chưa đăng nhập -->
                        <?php if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user')): ?>
                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>"
                               class="btn btn-primary">Thêm vào giỏ hàng</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $index++; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-danger">Không có sản phẩm nào trong danh mục này.</p>
        <?php endif; ?>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>