<?php include 'app/views/shares/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="mb-0">
            <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn
        </h2>
    </div>
    <div class="card-body">
        <?php if (!empty($cart)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th style="width: 200px;">Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($cart as $id => $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="/PROJECTBANHANG/<?php echo htmlspecialchars($item['image']); ?>" 
                                                alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                class="img-thumbnail me-3" 
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light me-3 d-flex align-items-center justify-content-center" 
                                                style="width: 80px; height: 80px;">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ
                                </td>
                                <td class="align-middle">
                                    <div class="input-group" style="width: 150px;">
                                        <form action="/PROJECTBANHANG/Product/updateQuantity" method="post" class="d-flex">
                                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                            <button type="submit" name="action" value="decrease" 
                                                class="btn btn-outline-secondary">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="text" class="form-control text-center" 
                                                value="<?php echo (int)$item['quantity']; ?>" readonly>
                                            <button type="submit" name="action" value="increase" 
                                                class="btn btn-outline-secondary">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="align-middle text-end">
                                    <strong><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</strong>
                                </td>
                                <td class="align-middle text-end">
                                    <form action="/PROJECTBANHANG/Product/updateQuantity" method="post" 
                                        onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="action" value="remove" 
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td class="text-end">
                                <h4 class="text-success mb-0">
                                    <?php echo number_format($total, 0, ',', '.'); ?> VNĐ
                                </h4>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/PROJECTBANHANG/Product" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
                <a href="/PROJECTBANHANG/Product/checkout" class="btn btn-success">
                    <i class="fas fa-credit-card me-2"></i>Thanh toán
                </a>
            </div>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Giỏ hàng của bạn đang trống</h4>
                <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng của bạn</p>
                <a href="/PROJECTBANHANG/Product" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
