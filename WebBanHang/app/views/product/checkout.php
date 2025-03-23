<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <!-- Các bước -->
    <div class="steps mb-4">
        <div class="step">
            <span class="step-icon">🛒</span> Giỏ hàng
        </div>
        <div class="step active">
            <span class="step-icon">📝</span> Thông tin đặt hàng
        </div>
        <div class="step">
            <span class="step-icon">💳</span> Thanh toán
        </div>
        <div class="step">
            <span class="step-icon">✅</span> Hoàn tất
        </div>
    </div>

    <h1 class="text-center mb-4">Thông tin khách mua hàng</h1>

    <?php
    // Tính tổng tiền từ giỏ hàng đã chọn
    $subtotal = 0;
    if (isset($_SESSION['selected_cart'])) {
        foreach ($_SESSION['selected_cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    $discount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0; // Giảm giá
    $total_price = $subtotal - $discount;
    ?>

    <form method="POST" action="/webbanhang/Product/processCheckout">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="title" id="mr" value="Anh" checked>
                            <label class="form-check-label" for="mr">Anh</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="title" id="ms" value="Chị">
                            <label class="form-check-label" for="ms">Chị</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nhập họ tên:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Nhập số điện thoại:</label>
                            <input type="text" id="phone" name="phone" class="form-control" required>
                        </div>
                    </div>
                </div>

                <h4>Hồ Chí Minh</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="district">Quận:</label>
                            <input type="text" id="district" name="district" class="form-control" required value="Quận 11">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ward">Số nhà, tên đường:</label>
                            <input type="text" id="ward" name="ward" class="form-control" required value="1 Hàn Hải Nguyên">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Phường:</label>
                    <input type="text" id="address" name="address" class="form-control" required value="Phường 16">
                </div>
                <div class="form-group">
                    <label for="note">Lưu ý, yêu cầu của khách (Không bắt buộc):</label>
                    <textarea id="note" name="note" class="form-control"></textarea>
                </div>

                <div class="cart-summary mt-4 p-3 border rounded">
                    <?php if ($discount > 0): ?>
                        <p><strong>Giảm giá:</strong> -<?php echo number_format($discount, 0, ',', '.'); ?>đ</p>
                    <?php endif; ?>
                    <p><strong>Tổng tiền:</strong> <span id="total-price"><?php echo number_format($total_price, 0, ',', '.'); ?>đ</span></p>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-checkout">Đặt hàng ngay</button>
        </div>
    </form>

    <p class="text-center mt-3 text-muted">Bạn có thể chọn hình thức thanh toán sau khi đặt hàng</p>
</div>

<?php include 'app/views/shares/footer.php'; ?>