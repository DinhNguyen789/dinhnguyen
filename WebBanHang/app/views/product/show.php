<?php
ob_clean();
include 'app/views/shares/header.php';
?>

<div class="container mt-5">
    <div class="row product-detail">
        <!-- Cột hình ảnh -->
        <div class="col-md-6">
            <div class="product-image">
                <?php if ($product->image): ?>
                    <img src="/webbanhang/<?php echo $product->image; ?>" class="img-fluid" alt="Hình sản phẩm">
                <?php else: ?>
                    <p class="text-center text-muted">Không có hình ảnh</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cột thông tin -->
        <div class="col-md-6">
            <div class="product-info">
                <h1 class="product-title"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="product-price"><strong>Giá:</strong> <?php echo number_format($product->price, 0, ',', '.'); ?> VND</p>
                <p class="product-description"><strong>Mô tả:</strong> <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                
                <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary btn-add-to-cart">Thêm vào giỏ hàng</a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>