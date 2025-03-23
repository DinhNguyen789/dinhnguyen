<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <!-- Các bước -->
    <div class="steps mb-4">
        <div class="step">
            <span class="step-icon">🛒</span> Giỏ hàng
        </div>
        <div class="step">
            <span class="step-icon">📝</span> Thông tin đặt hàng
        </div>
        <div class="step">
            <span class="step-icon">💳</span> Thanh toán
        </div>
        <div class="step active">
            <span class="step-icon">✅</span> Hoàn tất
        </div>
    </div>

    <h1 class="text-center mb-4">Thanh toán thành công!</h1>
    <p class="text-center">Cảm ơn bạn đã mua sắm tại DinhNguyenApple. Dưới đây là thông tin đơn hàng của bạn:</p>

    <div class="order-details p-4 border rounded">
        <h3>Thông tin đơn hàng</h3>
        <p><strong>Mã đơn hàng:</strong> #<?php echo htmlspecialchars($orderDetails['order_id']); ?></p>
        <p><strong>Ngày đặt hàng:</strong> <?php echo htmlspecialchars($orderDetails['created_at']); ?></p>
        <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($orderDetails['title'] . ' ' . $orderDetails['name']); ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($orderDetails['phone']); ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($orderDetails['ward'] . ', ' . $orderDetails['address'] . ', ' . $orderDetails['district'] . ', Hồ Chí Minh'); ?></p>
        <?php if (!empty($orderDetails['note'])): ?>
            <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($orderDetails['note']); ?></p>
        <?php endif; ?>

        <table class="order-table table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                foreach ($orderDetails['items'] as $item): 
                    $subtotal += $item['price'] * $item['quantity'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Tạm tính:</strong></td>
                    <td><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                </tr>
                <?php if ($orderDetails['discount'] > 0): ?>
                    <tr>
                        <td colspan="3"><strong>Giảm giá:</strong></td>
                        <td>-<?php echo number_format($orderDetails['discount'], 0, ',', '.'); ?>đ</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="3"><strong>Tổng cộng:</strong></td>
                    <td><strong><?php echo number_format($orderDetails['total_price'], 0, ',', '.'); ?>đ</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="actions mt-4 text-center">
        <a href="/webbanhang/Product" class="btn btn-primary">Quay lại trang chủ</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>