<?php include 'app/views/shares/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="mb-0">
            <i class="fas fa-list me-2"></i>Danh sách sản phẩm
        </h2>
    </div>
    <div class="card-body">
        <?php if (SessionHelper::isAdmin()): ?>
            <a href="/PROJECTBANHANG/Product/add" class="btn btn-success mb-4">
                <i class="fas fa-plus me-1"></i>Thêm sản phẩm mới
            </a>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if ($product->image): ?>
                            <img src="/PROJECTBANHANG/<?php echo $product->image; ?>" 
                                class="card-img-top" 
                                alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/PROJECTBANHANG/Product/show/<?php echo $product->id; ?>" 
                                   class="text-decoration-none">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            <p class="card-text text-truncate">
                                <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                            <p class="card-text">
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </p>
                            <h6 class="card-subtitle mb-3 text-danger fw-bold">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                            </h6>
                            
                            <div class="d-flex gap-2">
                                <a href="/PROJECTBANHANG/Product/addToCart/<?php echo $product->id; ?>" 
                                   class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                </a>
                                
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <div class="btn-group">
                                        <a href="/PROJECTBANHANG/Product/edit/<?php echo $product->id; ?>" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/PROJECTBANHANG/Product/delete/<?php echo $product->id; ?>" 
                                           class="btn btn-danger delete-btn"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>