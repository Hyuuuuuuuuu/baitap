<?php include 'app/views/shares/header.php'; ?>
<h1>Danh sách sản phẩm</h1>
<div id="alert-container"></div>
<a href="/PROJECTBANHANG/Product/add" class="btn btn-success mb-2">Thêm sản phẩm mới</a>
<ul class="list-group">
<?php foreach ($products as $product): ?>
<li class="list-group-item" id="product-<?php echo $product->id; ?>">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2>
                <a href="/PROJECTBANHANG/Product/show/<?php echo $product->id; ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h2>
            <p class="text-muted mb-2"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="mb-1">
                <strong>Giá:</strong> 
                <span class="text-danger"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span>
            </p>
            <p class="mb-2">
                <strong>Danh mục:</strong> 
                <span class="badge bg-info"><?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <?php if (SessionHelper::isAdmin()): ?>
                <a href="/PROJECTBANHANG/Product/edit/<?php echo $product->id; ?>" 
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <button type="button"
                   class="btn btn-danger btn-sm delete-product"
                   data-id="<?php echo $product->id; ?>">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            <?php endif; ?>
            <a href="/PROJECTBANHANG/Product/addToCart/<?php echo $product->id; ?>" 
               class="btn btn-primary btn-sm">
                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
            </a>
        </div>
    </div>
</li>
<?php endforeach; ?>
</ul>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hàm hiển thị thông báo
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.appendChild(alert);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }

    // Xử lý sự kiện xóa sản phẩm
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.id;
            const productElement = document.getElementById(`product-${productId}`);
            
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                return;
            }

            // Disable nút xóa và thêm loading state
            this.disabled = true;
            const originalContent = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...';

            try {
                const response = await fetch(`/PROJECTBANHANG/api/product/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    // Xóa phần tử khỏi DOM với animation
                    productElement.style.transition = 'all 0.3s ease';
                    productElement.style.opacity = '0';
                    productElement.style.height = '0';
                    setTimeout(() => {
                        productElement.remove();
                    }, 300);
                    
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message, 'danger');
                    // Restore nút xóa
                    this.disabled = false;
                    this.innerHTML = originalContent;
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi xóa sản phẩm', 'danger');
                // Restore nút xóa
                this.disabled = false;
                this.innerHTML = originalContent;
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>