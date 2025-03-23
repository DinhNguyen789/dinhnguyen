<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <!-- Các bước -->
    <div class="steps mb-4">
        <div class="step active">
            <span class="step-icon">🛒</span> Giỏ hàng
        </div>
        <div class="step">
            <span class="step-icon">📝</span> Thông tin đặt hàng
        </div>
        <div class="step">
            <span class="step-icon">💳</span> Thanh toán
        </div>
        <div class="step">
            <span class="step-icon">✅</span> Hoàn tất
        </div>
    </div>

    <h1 class="text-center mb-4">Giỏ hàng</h1>

    <?php if (isset($error)): ?>
        <p class="text-center text-danger"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!empty($cart)): ?>
        <form id="cart-form" action="/webbanhang/Product/checkout" method="POST">
            <div class="cart-items">
                <?php foreach ($cart as $index => $item): ?>
                    <div class="cart-item d-flex align-items-center mb-3 p-3 border rounded">
                        <!-- Checkbox chọn sản phẩm -->
                        <div class="cart-item-select mr-3">
                            <input type="checkbox" name="selected_items[]" value="<?php echo $index; ?>" class="select-item" onchange="updateTotal()" checked>
                        </div>

                        <!-- Hình ảnh sản phẩm -->
                        <div class="cart-item-image mr-3">
                            <img src="/webbanhang/<?php echo $item['image']; ?>" alt="Hình sản phẩm" class="img-fluid" style="max-width: 100px;">
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="cart-item-info flex-grow-1">
                            <h5><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="cart-item-price" data-price="<?php echo $item['price']; ?>">
                                Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                            </p>
                        </div>

                        <!-- Tăng giảm số lượng -->
                        <div class="cart-item-quantity mr-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(<?php echo $index; ?>, -1)">−</button>
                            <input type="text" name="quantities[<?php echo $index; ?>]" value="<?php echo $item['quantity']; ?>" class="quantity-input" readonly>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(<?php echo $index; ?>, 1)">+</button>
                        </div>

                        <!-- Nút xóa -->
                        <div class="cart-item-remove">
                            <a href="/webbanhang/Product/removeFromCart/<?php echo $index; ?>" class="btn btn-danger btn-sm">Xóa</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng tiền và nút hành động -->
            <div class="cart-summary mt-4 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Tổng tiền: <span id="total-price">0đ</span></h5>
                    <button type="submit" class="btn btn-checkout">Thanh toán</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <p class="text-center text-danger">Giỏ hàng trống.</p>
    <?php endif; ?>
</div>

<script>
// Tăng giảm số lượng
function updateQuantity(index, change) {
    const quantityInput = document.getElementsByName(`quantities[${index}]`)[0];
    let quantity = parseInt(quantityInput.value);
    quantity = Math.max(1, quantity + change); // Không cho phép số lượng nhỏ hơn 1
    quantityInput.value = quantity;
    updateTotal();
}

// Tính tổng tiền
function updateTotal() {
    let total = 0;
    const items = document.querySelectorAll('.cart-item');
    items.forEach(item => {
        const checkbox = item.querySelector('.select-item');
        if (checkbox.checked) {
            const price = parseFloat(item.querySelector('.cart-item-price').dataset.price);
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            total += price * quantity;
        }
    });
    document.getElementById('total-price').textContent = total.toLocaleString('vi-VN') + 'đ';
}

// Tính tổng tiền ban đầu khi tải trang
document.addEventListener('DOMContentLoaded', updateTotal);
</script>

<?php include 'app/views/shares/footer.php'; ?>