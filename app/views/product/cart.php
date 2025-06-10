<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">🛒 Giỏ hàng</h1>

    <?php if (!empty($cart)): ?>
        <ul class="list-group">
            <?php 
            $total = 0;
            foreach ($cart as $id => $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <li class="list-group-item">
                    <div class="row align-items-center">
                        <!-- Hình ảnh sản phẩm -->
                        <div class="col-md-2">
                            <?php if (!empty($item['image'])): ?>
                                <img src="/PROJECTBANHANG/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" class="img-fluid rounded">
                            <?php else: ?>
                                <img src="/PROJECTBANHANG/images/no-image.png" alt="No Image" class="img-fluid rounded">
                            <?php endif; ?>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="col-md-4">
                            <h5><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="text-danger font-weight-bold">
                                Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?> VND
                            </p>
                        </div>

                        <!-- Số lượng có nút ➖➕ -->
                        <div class="col-md-6">
                            <form action="/PROJECTBANHANG/Product/updateQuantity" method="post" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">

                                <button type="submit" name="action" value="decrease" class="btn btn-outline-secondary btn-sm">➖</button>

                                <span class="mx-2"><?php echo (int)$item['quantity']; ?></span>

                                <button type="submit" name="action" value="increase" class="btn btn-outline-secondary btn-sm">➕</button>
                            </form>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Tổng tiền -->
        <div class="mt-4 text-right">
            <h4 class="text-success">
                🧾 Tổng tiền: <?php echo number_format($total, 0, ',', '.'); ?> VND
            </h4>
        </div>

        <!-- Nút điều hướng -->
        <div class="mt-4 d-flex justify-content-between">
            <a href="/PROJECTBANHANG/Product" class="btn btn-secondary">🛍️ Tiếp tục mua sắm</a>
            <a href="/PROJECTBANHANG/Product/checkout" class="btn btn-success">💳 Thanh toán</a>
        </div>

    <?php else: ?>
        <div class="alert alert-info text-center">
            <h4>Giỏ hàng của bạn đang trống.</h4>
            <a href="/PROJECTBANHANG/Product" class="btn btn-primary mt-3">Mua sắm ngay</a>
        </div>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>
